<?php

/**
 * Defines the app_command_WorkspaceNotes class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');

/**
 * @package Alchemis
 */
class app_command_WorkspaceNotes extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		echo "Here";
//		return self::statuses('CMD_OK');
		
		$this->post_initiative_id = $request->getProperty('post_initiative_id');
		if ($this->post_initiative_id == 'null' || $this->post_initiative_id == '')
		{
			$this->post_initiative_id = null;
		}
		
		$this->post_id = $request->getProperty('post_id');
		if ($this->post_id == 'null' || $this->post_id == '')
		{
			$this->post_id = null;
		}
		
		$this->initiative_id = $request->getProperty('initiative_id');
		if ($this->initiative_id == 'null' || $this->initiative_id == '')
		{
			$this->initiative_id = null;
		}
			
		if (!is_null($this->post_id))
		{
			$this->getRecordIds();
		}
		
		
		$this->company_id = $request->getProperty('company_id');
		if ($this->company_id == 'null' || $this->company_id == '')
		{
			$this->company_id = null;
		}
				
		$notes = '';
			
		if (!is_null($this->post_initiative_id))
		{		
			$request->setProperty('post_id', $this->post_id);
			$request->setProperty('initiative_id', $this->initiative_id);
			$request->setProperty('post_initiative_id', $this->post_initiative_id);
			$notes = app_domain_PostInitiative::findNotes($this->post_initiative_id);
		}
		elseif (!is_null($this->post_id) && !is_null($this->initiative_id))
		{
			$request->setProperty('post_id', $this->post_id);
			$request->setProperty('initiative_id', $this->initiative_id);
			$request->setProperty('post_initiative_id', $this->post_initiative_id);
			$notes = app_domain_PostInitiative::findByPostAndInitiativeForCurrentUser($this->post_id, $this->initiative_id);
		}
		elseif (!is_null($this->company_id) && !is_null($this->initiative_id))
		{
			$request->setProperty('post_id', $this->post_id);
			$request->setProperty('initiative_id', $this->initiative_id);
			$request->setProperty('post_initiative_id', $this->post_initiative_id);
			$notes = app_domain_PostInitiative::findNotesByCompanyAndInitiative($this->company_id, $this->initiative_id);
		}
		
		$request->setObject('notes', $notes);
		return self::statuses('CMD_OK');
	}
	
	protected function getRecordIds()
	{
		// if we have a post initiative id then we should use this to calculate the initiative id
		// and post id				
		if (!is_null($this->post_initiative_id))
		{
			$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
			if (is_object($post_initiative))
			{
				$this->post_id = $post_initiative->getPostId();
				// its safe to set the initiative id at this point since a post initiative
				// record does exist
				$this->initiative_id = $post_initiative->getInitiativeId();
			}
			else
			{
				throw new exception ("Invalid post initiative id supplied");
			}
		}
		else
		{
			// check we have a post available 
			if (is_null($this->post_id))
			{
				throw new exception ("No post id specified");
			}
			
			// work out the initiative_id
			if (is_null($this->initiative_id))
			{
				// see if there are any post initiative records for this post/user
				// combo 
				$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
				
				// get a default post_initiative_id
				if (count($post_initiatives) > 0)
				{
					$this->initiative_id = $post_initiatives[0]['initiative_id'];
					$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
				}
				else
				{
					$this->initiative_id = null;
					$this->post_initiative_id = null;	
				}
				
			}
			else
			{
				// if an initiative is passed in then need to see if there is a 
				// post initiative record for this post/initiative combo available to the 
				// current user. If not, then we need to if there are any post initiative 
				// records for this post/user combo. If so then set the initiative id equal
				// to the first one of these
				$post_initiative = app_domain_PostInitiative::findByPostAndInitiativeForCurrentUser($this->post_id, $this->initiative_id);
				
				//need to do check to see if any other post intiatives exist for this user for this post
				if (!is_object($post_initiative))
				{	
					$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
					
					if (count($post_initiatives) > 0)
					{
						// DMC: new section 12/01/2009 -
						$this->found_default_initiative = false;
						foreach ($post_initiatives as $item)
						{
							if ($item['initiative_id'] == $this->initiative_id)
							{
								$this->found_default_initiative = true; 
								$this->initiative_id = $item['initiative_id'];
								$this->post_initiative_id = $item['post_initiative_id'];
							}
						}
						
						if (!$this->found_default_initiative)
						{
//							$this->initiative_id = $post_initiatives[0]['initiative_id'];
//							$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
						}
					}
					else
					{
						$this->post_initiative_id = null;
					}
					
					// End section------------------
					
					// Removed section------------------------------
//					// get a default post_initiative_id
//					if (count($post_initiatives) > 0)
//					{
//						$this->initiative_id = $post_initiatives[0]['initiative_id'];
//						$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
////						$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
//					}
//					else
//					{
//						$this->post_initiative_id = null;
//					}
					//-------------------------------
				}
				else
				{
					$this->post_initiative_id = $post_initiative->getId();
				}						
				
			}
		}	
		
	}
	
//	protected function getRecordIds()
//	{
//		// if we have a post initiative id then we should use this to calculate the initiative id
//		// and post id				
//		if (!is_null($this->post_initiative_id))
//		{
//			$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
//			if (is_object($post_initiative))
//			{
//				$this->post_id = $post_initiative->getPostId();
//				// its safe to set the initiative id at this point since a post initiative
//				// record does exist
//				$this->initiative_id = $post_initiative->getInitiativeId();
//			}
//			else
//			{
//				throw new exception ("Invalid post initiative id supplied");
//			}
//		}
//		else
//		{
//			// check we have a post available 
//			if (is_null($this->post_id))
//			{
//				throw new exception ("No post id specified");
//			}
//			
//			// work out the initiative_id
//			if (is_null($this->initiative_id))
//			{
//				// see if there are any post initiative records for this post/user
//				// combo 
//				$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
//				
//				// get a default post_initiative_id
//				if (count($post_initiatives) > 0)
//				{
//					$this->initiative_id = $post_initiatives[0]['initiative_id'];
//					$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
//				}
//				else
//				{
//					$this->initiative_id = null;
//					$this->post_initiative_id = null;	
//				}
//				
//			}
//			else
//			{
//				// if an initiative is passed in then need to see if there is a 
//				// post initiative record for this post/initiative combo available to the 
//				// current user. If not, then we need to if there are any post initiative 
//				// records for this post/user combo. If so then set the initiative id equal
//				// to the first one of these
//				$post_initiative = app_domain_PostInitiative::findByPostAndInitiativeForCurrentUser($this->post_id, $this->initiative_id);
//				
//				//need to do check to see if any other post intiatives exist for this user for this post
//				if (!is_object($post_initiative))
//				{	
//					$post_initiatives = app_domain_Post::findPostInitiativesForCurrentUser($this->post_id);
//					// get a default post_initiative_id
//					if (count($post_initiatives) > 0)
//					{
//						$this->initiative_id = $post_initiatives[0]['initiative_id'];
//						$this->post_initiative_id = $post_initiatives[0]['post_initiative_id'];
////						$post_initiative = app_domain_PostInitiative::find($this->post_initiative_id);
//					}
//					else
//					{
//						$this->post_initiative_id = null;
//					}
//				}
//				else
//				{
//					$this->post_initiative_id = $post_initiative->getId();
//				}						
//				
//			}
//		}	
//		
//	}
	
}

?>