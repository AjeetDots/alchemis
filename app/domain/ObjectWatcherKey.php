<?php

/**
 * Defines the app_domain_ObjectWatcherKey interface. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */


/**
 * @package Framework
 */
interface app_domain_ObjectWatcherKey
{
	/**
	 * Returns additional string component to be used as part of the glabal key 
	 * implemented by app_domain_ObjectWatcher.
	 * @see app_domain_ObjectWatcher::globalKey()
	 * @return string
	 */
	public function objectWatcherKey();
}

?>