 <?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Company.php');
require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_Company objects.
 * @package alchemis
 */
class app_command_AjaxCompany extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		switch ($this->request->cmd_action)
		{
			case 'update_name':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				
				// call the relevant accessors or mutators
				// $this->company->setName(mb_convert_encoding($this->request->name, 'ISO-8859-1', 'UTF-8'));
				$this->company->setName($this->request->name);
				$this->company->commit();
				break;
			case 'dial_number_request':
				$number = $this->request->number;
				$tpsResponse = app_lib_Tps::check($number,(isset($this->request->refresh_number) && $this->request->refresh_number)?TRUE:FALSE);
				echo json_encode([
					"warnings"=> [],
					"notices"=> [],
					"data"=> [
						[
							"cmd_action" =>	$this->request->cmd_action,
							"data" => $tpsResponse
						]
					]
				]);
				exit();
				break;
			case 'update_telephone':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				
				// call the relevant accessors or mutators
				// $this->company->setTelephone(mb_convert_encoding($this->request->telephone, 'ISO-8859-1', 'UTF-8'));
				$this->company->setTelephone($this->request->telephone);
				$this->company->commit();
				break;
			case 'update_additional_info':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				
				// call the relevant accessors or mutators
				// $this->company->setAdditionalInfo(mb_convert_encoding($this->request->additional_info, 'ISO-8859-1', 'UTF-8'));
				$this->company->setAdditionalInfo($this->request->additional_info);
				$this->company->commit();
				$this->request->company_detail = $this->getCompanyDetail();
				break;
			case 'update_telephone_tps':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				// call the relevant accessors or mutators
				$this->company->setTelephoneTps(!$this->company->getTelephoneTps());
				$this->company->commit();
				$this->request->telephone_tps = $this->company->getTelephoneTps();
				break;
			case 'update_website':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				
				// call the relevant accessors or mutators
				// $this->company->setWebsite(mb_convert_encoding($this->request->website, 'ISO-8859-1', 'UTF-8'));
				$this->company->setWebsite($this->request->website);
				$this->company->commit();
				break;
			case 'get_results_start_with':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				$results = app_domain_Company::findByNameStart($this->request->search_item);
				$this->request->results = $this->getPostEditLocationResults($results); 
				break;
			case 'get_company_detail':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				$this->request->company_detail = $this->getCompanyDetail();
				break;
			case 'get_company_detail_1':
				$this->company_id = $this->request->item_id;
			
				if ($this->company_id)
				{
					$this->company = app_domain_Company::find($this->company_id);
				}
				else
				{
					$this->company = new app_domain_Company();
				} 
				$this->request->company_detail = $this->getCompanyDetail();
				break;
			case 'get_workspace_notes_by_company':
				$this->company_id = $this->request->item_id;
				
				if ($this->request->initiative_id != 'null')
				{
					$this->initiative_id = $this->request->initiative_id;
				}
				else
				{
					$this->initiative_id = null;
				}
				$this->request->workspace_notes = $this->getWorkspaceNotesByCompany();
				break;
			case 'update_note':
				$this->note_id = $this->request->item_id;
			
				if ($this->note_id)
				{
					$this->note = app_domain_CompanyNote::find($this->note_id);
				}
				else
				{
					// raise error
				} 
				
				// call the relevant accessors or mutators
				// $this->note->setNote(mb_convert_encoding($this->request->note, 'ISO-8859-1', 'UTF-8'));
				$this->note->setNote($this->request->note);
				$this->note->commit();
				break;
			default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null)
		{
			$this->request->item_id = $this->company->getId();
		}
		
		array_push($this->response->data, $this->request);
		// echo "<pre>";
		// echo print_r($this->response->data);
		// echo "</pre>";
	}
	
	protected function getPostEditLocationResults($results)
	{
		
		$return_data = array();
		$companies = $results->toArray();

		foreach ($companies as $item)
		{
			$return_data[] = array(	'id' 		=> $item->getId(),
									'name' 		=> $item->getName(),
									'address' 	=> $item->getSiteAddress(null, 'paragraph'));
		}
		
		return $return_data;
	}
	
	protected function getCompanyDetail()
	{
		$return_data = new stdClass();
		
		// Get note count
		$company_note_count = app_domain_CompanyNote::findCountByCompanyId($this->company_id);
		
		// Get posts
		$company_posts_job_title = app_domain_Company::findPostsOrderByJobTitle($this->company->getId());
			
		$post_id = $this->request->post_id;
		$post_initiative_id = $this->request->post_initiative_id;
		
		// Get clients contacted for this post
		if (is_null($post_id) || $post_id == '' || $post_id == null || $post_id == 'null')
		{
			if (count($company_posts_job_title) > 0)
			{
				$post_id = $company_posts_job_title[0]['id'];
			}
			else
			{
				$post_id = null;
			}
		}
		
		if (!is_null($post_id))
		{
			$post = app_domain_Post::find($post_id);
		}
		
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('company', $this->company);
		$smarty->assign('company_posts_job_title', $company_posts_job_title);
		$smarty->assign('post', $post);
		$company = app_model_Company::find($this->company_id);
		$parent_companies = $company->parents();
		$smarty->assign('parent_companies', $parent_companies);
		// print_r($this->request);die;
		// Get TPS Response from Companies Phone Number
		// $tpsResponse = app_lib_Tps::check($this->company->getTelephone());
		$tpsResponse = app_lib_Tps::check($this->company->getTelephone(),(isset($this->request->refresh_number) && $this->request->refresh_number)?TRUE:FALSE);
		$smarty->assign('companyTelephoneTpsStatus', (array)$tpsResponse);
		
		// $return_data->template = mb_convert_encoding($smarty->fetch('WorkspaceCompany.tpl'), 'UTF-8', "HTML-ENTITIES");
		$return_data->template = $smarty->fetch('WorkspaceCompany.tpl');
		
		$return_data->company_note_count = $company_note_count;
		$return_data->post_id = $post_id;
		$return_data->post_initiative_id = $post_initiative_id;
		$return_data->parent_companies = $parent_companies;

		return $return_data;
	}
	
	protected function getWorkspaceNotesByCompany()
	{
		$return_data = new stdClass();
		
		$notes = app_domain_PostInitiative::findNotesByCompanyAndInitiative($this->company_id, $this->initiative_id);

		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		
		$smarty->assign('company_id', $this->company_id);
		$smarty->assign('initiative_id', $this->initiative_id);
		$smarty->assign('notes', $notes);
		
		$return_data->template = $smarty->fetch('WorkspaceNotes.tpl');
			
		return $return_data;
	}
}

?>