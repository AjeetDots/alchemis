<?php

/**
 * Defines the app_base_Observer interface. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

interface app_base_Observer
{
	public function update(app_base_Observable $observable);
}

?>