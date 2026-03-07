<?php

/**
 * Defines the app_base_Observable interface. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

interface app_base_Observable
{
	public function attach(app_base_Observer $observer);
	public function detach(app_base_Observer $observer);
	public function notify();
}

?>