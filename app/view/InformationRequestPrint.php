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
class app_view_InformationRequestPrint extends app_view_View
{
	protected function doExecute()
	{
		// Get feedback
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		
		$action = $this->request->getObject('action');
		$this->smarty->assign('action',         $action);
				
		$company = $this->request->getObject('company');
		$this->smarty->assign('company',         $company);
				
		// Post
		$post =  $this->request->getObject('post');
		$this->smarty->assign('post',$post);
		$contacts = $post->getContacts();
//		print_r($contacts->toArray());
		$contact = $post->getContact();
//		print_r($contact);
		$this->smarty->assign('contact', $contact);
//		echo '</pre>';
		
		$this->smarty->assign('discipline_note', $this->request->getProperty('discipline_note'));
		$this->smarty->assign('characteristics', $this->request->getObject('characteristics'));
		$this->smarty->assign('initiative', $this->request->getObject('initiative'));
		
		// Client
//		$this->smarty->assign('initiative', $this->request->getObject('initiative'));
//		$client = $this->request->getObject('client');
		$this->smarty->assign('client', $this->request->getObject('client'));
		
		
//		echo "<pre>";
//		print_r($this->request->getObject('notes'));
//		echo "<pre>";
		$this->smarty->assign('notes', $this->request->getObject('notes'));
		
		$this->smarty->display('InformationRequestPrint.tpl');
	}

}

?>