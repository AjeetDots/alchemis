<?php

/**
 * Defines the app_view_MessageSummary class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_MessageSummary extends app_view_View
{
	protected function doExecute()
	{
		$messages = array(	array(	'from'     => 'Jim Piper',
									'subject'  => 'Client Reports',
									'received' => '4 Dec 2006 11:35:26'),
							array(	'from'     => 'Ian Forbes',
									'subject'  => 'Profitability',
									'received' => '28 Nov 2006 15:08:37'),
							array(	'from'     => 'Dave Newman',
									'subject'  => 'NBM Stats',
									'received' => '15 Nov 2006 09:49:20'));
		$this->smarty->assign('messages', $messages);
		$this->smarty->display('MessageSummary.tpl');
	}
}

?>