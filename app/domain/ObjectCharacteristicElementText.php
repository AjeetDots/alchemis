<?php

/**
 * Defines the app_domain_ObjectCharacteristicElementText class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ObjectCharacteristicElement.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristicElementText extends app_domain_ObjectCharacteristicElement 
{
	/**
	 * Sets the characteristic value.
	 * @param string $value
	 */
	public function setValue($value)
	{
		if (is_string($value))
		{
			$this->value = $value;
			$this->markDirty();
		}
		else
		{
			throw new Exception('Type is not string');
		}
	}

}

?>