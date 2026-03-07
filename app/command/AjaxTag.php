<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2007 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Tag.php');
require_once('app/mapper/PostMapper.php');
require_once('app/mapper/PostInitiativeMapper.php');

/**
 * Command class to handle Ajax operations on app_domain_Tag objects.
 * @package alchemis
 */
class app_command_AjaxTag extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug)
		{
			echo "<pre>";
			echo print_r($this->request);
			echo "</pre>";
		}
		
		// Instantiate the object
		$tag_id = $this->request->item_id;
			
		if ($tag_id)
		{
			$tag = app_domain_Tag::find($tag_id);
		}
		else
		{
			$tag = new app_domain_Tag();
		} 
		
		switch ($this->request->cmd_action)
		{
			case 'insertCompanyTag':
				// call the relevant accessors or mutators
				if ($result = $this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$company = app_domain_Company::find($this->request->parent_object_id);
					$tag->setParentDomainObject($company);
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			case 'deleteCompanyTag':
				// call the relevant accessors or mutators
				if ($this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$company = app_domain_Company::find($this->request->parent_object_id);
					$tag->setParentDomainObject($company);
					$tag->markDeleted();
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			case 'insertPostTag':
				// call the relevant accessors or mutators
				if ($result = $this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$post = app_domain_Post::find($this->request->parent_object_id);
					$tag->setParentDomainObject($post);
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			case 'deletePostTag':
				// call the relevant accessors or mutators
				if ($this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$post = app_domain_Post::find($this->request->parent_object_id);
					$tag->setParentDomainObject($post);
					$tag->markDeleted();
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			case 'insertPostInitiativeTag':
				// call the relevant accessors or mutators
				if ($result = $this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$post_initiative = app_domain_PostInitiative::find($this->request->parent_object_id);
					$tag->setParentDomainObject($post_initiative);
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			case 'deletePostInitiativeTag':
				// call the relevant accessors or mutators
				if ($this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$post_initiative = app_domain_PostInitiative::find($this->request->parent_object_id);
					$tag->setParentDomainObject($post_initiative);
					$tag->markDeleted();
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			
			case 'update':
				// call the relevant accessors or mutators
				if ($this->processAccessorsMutators($tag, $this->request, 'set'))
				{
					$tag->commit();
				}
				else
				{
					array_push($this->response->warnings, 'Trying to instantiate unknown function.');
					exit();
				}
				break;
			default:
				break;
		}
		
		// Return result data
		// Update the item_id element of the request string in case we have added a
		// new object. Useful to return the new id
		if ($this->request->item_id == null)
		{
			$this->request->item_id = $tag->getId();
		}

		array_push($this->response->data, $this->request);
		
	}
	
	

}







?>