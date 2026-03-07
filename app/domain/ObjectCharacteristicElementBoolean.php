<?php

/**
 * Defines the app_domain_ObjectCharacteristicElementBoolean class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ObjectCharacteristicElement.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristicElementBoolean extends app_domain_ObjectCharacteristicElement
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

	/**
	 * Returns the characteristic value
	 * @return mixed
	 */
	public function getValue()
	{
		if (is_null($this->value) || $this->value == '')
		{
			return '0';
		}
		else
		{
			return $this->value;
		}
	}

}

?>