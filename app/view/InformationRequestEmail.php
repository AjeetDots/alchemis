<?php

/**
 * Defines the app_view_InformationRequestPrint class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_InformationRequestEmail extends app_view_View
{
	protected function doExecute()
	{
		// Get feedback
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
		$this->smarty->assign('nbm_name', $this->request->getProperty('nbm_name'));
		$this->smarty->assign('nbm_email', $this->request->getProperty('nbm_email'));
		
		$action = $this->request->getObject('action');
		$this->smarty->assign('action',         $action);
				
		$company = $this->request->getObject('company');
		$this->smarty->assign('company',         $company);
				
		// Post
		$post =  $this->request->getObject('post');
		$this->smarty->assign('post',$post);
		$contacts = $post->getContacts();
		$contact = $post->getContact();
		$this->smarty->assign('contact', $contact);
		
		$this->smarty->assign('discipline_note', $this->request->getProperty('discipline_note'));
		$this->smarty->assign('characteristics', $this->request->getObject('characteristics'));
		$this->smarty->assign('initiative', $this->request->getObject('initiative'));
		
		// Client
		$this->smarty->assign('client', $this->request->getObject('client'));

		// Email has been sent?
		$this->smarty->assign('email_sent', $this->request->getObject('email_sent'));
		
		$this->smarty->assign('from_name',  $this->request->getObject('from_name'));
		$this->smarty->assign('from_email', $this->request->getObject('from_email'));
		$this->smarty->assign('to_name',    $this->request->getObject('to_name'));
		$this->smarty->assign('to_email',   $this->request->getObject('to_email'));
		$this->smarty->assign('subject',    $this->request->getObject('subject'));
		$this->smarty->assign('body',       $this->request->getObject('body'));
				
		$this->smarty->assign('notes', $this->request->getObject('notes'));
		
		$this->smarty->display('InformationRequestEmail.tpl');
	}

}

?>