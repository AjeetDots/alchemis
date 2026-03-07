<?php

/**
 * Defines the app_view_MeetingEmail class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_MeetingEmail extends app_view_View
{
	protected function doExecute()
	{
		// Get feedback
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
		$this->smarty->assign('nbm_name', $this->request->getProperty('nbm_name'));
		$this->smarty->assign('nbm_email', $this->request->getProperty('nbm_email'));
		
		$meeting = $this->request->getObject('meeting');
		$company = $this->request->getObject('company');
		
		// Meeting
		$this->smarty->assign('meeting', $meeting);
		
		// Post
		$post = $meeting->getPost();
		$this->smarty->assign('post', $post);

		// Contact
		$contacts = $post->getContacts();
		$contact = $post->getContact();
		$this->smarty->assign('contact', $contact);
		
		$this->smarty->assign('company',         $company);
//		$this->smarty->assign('characteristics', $company->getCharacteristics());

		$this->smarty->assign('actions', $this->request->getObject('actions'));
		
		$this->smarty->assign('discipline_note', $this->request->getProperty('discipline_note'));
		$this->smarty->assign('characteristics', $this->request->getObject('characteristics'));
		$this->smarty->assign('initiative', $this->request->getObject('initiative'));
		
		// Client
		$this->smarty->assign('initiative', $this->request->getObject('initiative'));
		$client = $this->request->getObject('client');
		$this->smarty->assign('client', $client);
		
		// Email has been sent?
		$this->smarty->assign('email_sent', $this->request->getObject('email_sent'));
		
		$this->smarty->assign('from_name',  $this->request->getObject('from_name'));
		$this->smarty->assign('from_email', $this->request->getObject('from_email'));
		$this->smarty->assign('to_name',    $this->request->getObject('to_name'));
		$this->smarty->assign('to_email',   $this->request->getObject('to_email'));
		$this->smarty->assign('subject',    $this->request->getObject('subject'));
		$this->smarty->assign('body',       $this->request->getObject('body'));
		
		$this->smarty->assign('notes',           $this->request->getObject('notes'));
		
		$this->smarty->display('MeetingEmail.tpl');
	}

}

?>