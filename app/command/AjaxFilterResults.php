<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');

/**
 * Command class to handle Ajax operations on app_domain_FilterResults objects.
 * @package alchemis
 */
class app_command_AjaxFilterResults extends app_command_AjaxCommand
{

	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);

		switch ($this->request->cmd_action)
		{
			case 'bulk_update':
				$form_data = $this->request->form_data;

				// array to hold incoming field data arrays
				$company_data = array();
				$post_data = array();
				$client_data = array();

//				print(count($form_data));

				foreach ($form_data as $key => $data_item)
				{
					$temp = array();
					$temp = explode('_', $key);
					array_push($temp, $data_item);
					switch ($temp[1])
					{
						case 'company':
							$company_data[] = $temp[2];
							break;
						case 'post':
							$post_data[] = $temp[2];
							break;
						case 'client':
							$client_data[] = $temp[2];
							break;
						default:
							break;

					}
				}

				if (count($company_data) > 0 && $this->request->action_type == 'company')
				{
					$this->addRecords('app_domain_Company', $this->request->company_tag, $company_data, null);
				}

				if (count($post_data) > 0 && $this->request->action_type == 'post')
				{
					$this->addRecords('app_domain_Post', $this->request->post_tag, $post_data, null);
				}

				if (count($post_data) > 0 && $this->request->action_type == 'client')
				{
					$this->addRecords('app_domain_PostInitiative', $this->request->client_tag, $post_data, $this->request->initiative_id);
				}

				$this->request->return_data = 1;

			default:
				break;
		}

		// Return result data
		array_push($this->response->data, $this->request);

	}

	private function addRecords($type, $tag, $ids, $initiative_id)
	{

		foreach ($ids as $id)
		{
			$obj_tag = new app_domain_Tag();
			$obj_tag->setValue($tag);

			switch($type)
			{
				case 'app_domain_Company':
					$parent = app_domain_Company::find($id);
					$obj_tag->setCategoryId(2);
					break;
				case 'app_domain_Post':
					$parent = app_domain_Post::find($id);
					$obj_tag->setCategoryId(2);
					break;
				case 'app_domain_PostInitiative':
					$parent = app_domain_PostInitiative::findByPostAndInitiative($id, $initiative_id);
					if (!is_object($parent))
					{
						$parent = new app_domain_PostInitiative();
						$parent->setPostId($id);
						$parent->setInitiativeId($initiative_id);
						$parent->setStatusId(7); // fresh lead
						$parent->setNextActionBy(1); // alchemis
						$parent->commit();
					}
					$obj_tag->setCategoryId(3);
					break;
				default:
					break;
			}
			$obj_tag->setParentDomainObject($parent);
			$obj_tag->commit();

		}
	}

}

?>