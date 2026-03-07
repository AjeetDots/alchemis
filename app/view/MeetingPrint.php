<?php

/**
 * Defines the app_view_MeetingPrint class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_MeetingPrint extends app_view_View
{
	protected function doExecute()
	{
		header('Content-type: text/html; charset=UTF-8');

		// Get feedback
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
		$meeting = $this->request->getObject('meeting');
		$company = $this->request->getObject('company');
		
		// Meeting
		$this->smarty->assign('meeting', $meeting);
		
		// Post
		$post = $meeting->getPost();
		$this->smarty->assign('post', $post);
//		echo $post->getId();
		// Contact
//		echo '<pre>';
		$contacts = $post->getContacts();
//		print_r($contacts->toArray());
		$contact = $post->getContact();
//		print_r($contact);
		$this->smarty->assign('contact', $contact);
//		echo '</pre>';
		
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
		
		
//		echo "<pre>";
//		print_r($this->request->getObject('notes'));
//		echo "<pre>";
		$this->smarty->assign('notes', $this->request->getObject('notes'));
		
		$this->smarty->display('MeetingPrint.tpl');
	}

}

?>