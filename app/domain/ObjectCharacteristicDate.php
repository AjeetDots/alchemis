<?php

/**
 * Defines the app_domain_ObjectCharacteristicDate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ObjectCharacteristic.php');
require_once('Utils/RegularExpression.class.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristicDate extends app_domain_ObjectCharacteristic 
{
	/**
	 * Sets the characteristic value.
	 * @param string $value
	 */
	public function setValue($value)
	{
		if (is_string($value) && (preg_match(REGEX_MYSQL_DATE, $value) || preg_match(REGEX_MYSQL_DATETIME, $value)))
		{
			$this->value = $value;
			$this->markDirty();
		}
		else
		{
			throw new Exception('Type is not a string and/or in a valid format');
		}
	}

}

?>