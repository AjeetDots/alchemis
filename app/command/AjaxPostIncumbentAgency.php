<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Post.php');
require_once('app/mapper/PostMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_PostInitiative objects.
 * @package alchemis
 */
class app_command_AjaxPostIncumbentAgency extends app_command_AjaxCommand
{
	
	protected $post_initiative;
	
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
//		// Instantiate the object
//		$post_id = $this->request->post_id;
				
		switch ($this->request->cmd_action)
		{
			case 'add_incumbent':
				// check if a post initiative already exists for this post/initiative combo
				if ($post_incumbent_agency = app_domain_PostIncumbentAgency::findByPostIdDisciplineIdAndAgencyCompanyId($this->request->post_id, $this->request->discipline_id, $this->request->agency_company_id))
				{
					// do nothing - a post initiative already exists
					$this->request->currently_exists = true;
				}
				else
				{
					// create new post incumbent agency domain object					
					$post_incumbent_agency = new app_domain_PostIncumbentAgency();
					// set properties of the newly created post incumbent agency
					$post_incumbent_agency->setPostId($this->request->post_id);
					$post_incumbent_agency->setDisciplineId($this->request->discipline_id);
					$post_incumbent_agency->setAgencyCompanyId($this->request->agency_company_id);
					$post_incumbent_agency->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
					$post_incumbent_agency->setLastUpdatedAt(date('Y-m-d H:i:s'));
					$post_incumbent_agency->commit();

					// if this new post_incumbent_agency_id has been added via the communication screen we need to 
					// add the new incumbent_agency_id to the session so we can add a communication_id when the 
					// parent communication is saved
					$this->addPostIncumbentAgenciesToCommunicationSession($post_incumbent_agency);
		
					$this->request->post_incumbent_agency_id = $post_incumbent_agency->getId();
					$this->request->currently_exists = false;
					$this->request->row_html = $this->getPostIncumbentAgencyLineHtml($post_incumbent_agency->getId(), $post_incumbent_agency->getAgencyCompanyId());
				}
				break;
			case 'confirm_incumbent':
				// confirm post incumbent agency details are corrent 
				$post_incumbent_agency = app_domain_PostIncumbentAgency::find($this->request->item_id);
				$post_incumbent_agency->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
				$post_incumbent_agency->setLastUpdatedAt(date('Y-m-d H:i:s'));
				$post_incumbent_agency->commit();
				
				// if this post_incumbent_agency_id has been confirmed via the communication screen we need to 
				// add the new incumbent_agency_id to the session so we can add a communication_id when the 
				// parent communication is saved
				$this->addPostIncumbentAgenciesToCommunicationSession($post_incumbent_agency);
					
				$this->request->img_html = app_base_ApplicationRegistry::getUrl() . 'app/view/images/icons/tick.png';
				
				break;
			case 'delete_incumbent':
				// delete post incumbent agency domain object					
				$post_incumbent_agency = app_domain_PostIncumbentAgency::find($this->request->item_id);
				$post_incumbent_agency->markDeleted();
				$post_incumbent_agency->commit();
				break;
			case 'add_incumbent_agency_company':
				// check if a company with this name already exists for this post/initiative combo
				if ($companies = app_domain_Company::findByNameEqual($this->request->agency_company_name))
				{
					if (count($companies->toRawArray()) > 0)
					{
						// company already exists
						$this->request->currently_exists = true;
					}
					else
					{
						// create new company domain object					
						$company = new app_domain_Company();
						// set properties of the newly created company
						$company->setName($this->request->agency_company_name);
						$company->commit();
						
						// associate the new object with tiered_characteristic for marketing services
						$object_tiered_characteristic = new app_domain_ObjectTieredCharacteristic();
						$object_tiered_characteristic->setParentObjectId($company->getId());
						$object_tiered_characteristic->setParentObjectType('app_domain_Company');
						$object_tiered_characteristic->setTieredCharacteristicId(18);
						$object_tiered_characteristic->setTier(0);
						$object_tiered_characteristic->commit();
						
						// we can't associate the new object with tiered_characteristic for 
						// the discipline_id passed in because we don't what tier we should use
						// TODO: do we need to add a tier dropdown to the add incumbent agency company screen?
						
						// create new post incumbent agency domain object					
						$post_incumbent_agency = new app_domain_PostIncumbentAgency();
						// set properties of the newly created post incumbent agency
						$post_incumbent_agency->setPostId($this->request->post_id);
						$post_incumbent_agency->setDisciplineId($this->request->discipline_id);
						$post_incumbent_agency->setAgencyCompanyId($company->getId());
						$post_incumbent_agency->setLastUpdatedBy($_SESSION['auth_session']['user']['id']);
						$post_incumbent_agency->setLastUpdatedAt(date('Y-m-d H:i:s'));
						$post_incumbent_agency->commit();
					
						// if this post_incumbent_agency_id has been confirmed via the communication screen we need to 
						// add the new incumbent_agency_id to the session so we can add a communication_id when the 
						// parent communication is saved
						$this->addPostIncumbentAgenciesToCommunicationSession($post_incumbent_agency);
				
						$this->request->post_incumbent_agency_id = $post_incumbent_agency->getId();
						$this->request->currently_exists = false;
						$this->request->row_html = $this->getPostIncumbentAgencyLineHtml($post_incumbent_agency->getId(), $post_incumbent_agency->getAgencyCompanyId());
					}
				}
				break;
			default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
//		if ($this->request->item_id == null && isset($this->post_initiative))
//		{
//			$this->request->item_id = $this->post_initiative->getId();
//		}
		
		array_push($this->response->data, $this->request);
		
	}
	
	protected function getPostIncumbentAgencyLineHtml($id, $company_id)
	{
		//get meeting information
		$company = app_domain_Company::find($company_id);
				
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('id', $id);
		$smarty->assign('name', $company->getName());
		$smarty->assign('address', $company->getSiteAddress(null, 'paragraph'));
		$smarty->assign('last_updated_at', date('Y-m-d'));
		
		return $smarty->fetch('html_PostIncumbentAgencyLine.tpl');

	}
	
	protected function addPostIncumbentAgenciesToCommunicationSession($post_incumbent_agency) 
	{
		if (isset($_SESSION['auth_session']['communication']))
		{
			if (!isset($_SESSION['auth_session']['communication']['post_incumbent_agencies']))
			{
				$_SESSION['auth_session']['communication']['post_incumbent_agencies'] = array();
			}
			$_SESSION['auth_session']['communication']['post_incumbent_agencies'][$post_incumbent_agency->getId()] = $post_incumbent_agency;
		}
	}
}

?>