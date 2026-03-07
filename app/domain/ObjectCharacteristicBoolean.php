<?php

/**
 * Defines the app_domain_ObjectCharacteristicBoolean class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ObjectCharacteristic.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristicBoolean extends app_domain_ObjectCharacteristic 
{
	/**
	 * Sets the characteristic value.
	 * @param boolean $value
	 */
	public function setValue($value)
	{
//		if (is_bool($value))
//		{
			$this->value = (bool)$value;
			$this->markDirty();
//		}
//		else
//		{
//			throw new Exception('Type is not bool');
//		}
	}

}

?>