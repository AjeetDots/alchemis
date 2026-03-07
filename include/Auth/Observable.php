<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

interface Observable
{
	function attach(Observer $observer);
//	function attach($observer);
	function detach(Observer $observer);
	function notify();
}

?>