<?php

/**
 * Defines the app_base_RuleValidator class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/ValidationError.php');
require_once('include/Utils/RegularExpression.class.php');

/**
 * A number of commonly used regular expressions are defined here.
 */
/*
define('REGEX_EMAIL_ADDRESS', '/^([0-9A-Za-z_-]|\.)+@(\w+\.)+\w+$/');
define('REGEX_INTEGER',       '/^-?\d+$/');
define('REGEX_INTEGER_POS',   '/^\d+$/');
define('REGEX_INTEGER_NEG',   '/^-\d+$/');
define('REGEX_DECIMAL',       '/^-?\d+(\.\d+)?$/');
define('REGEX_DECIMAL_POS',   '/^\d+(\.\d+)?$/');
define('REGEX_DECIMAL_NEG',   '/^-\d+(\.\d+)?$/');
define('REGEX_DATE',          '/^\d{4}-\d{2}-\d{2}$/');
define('REGEX_TIME',          '/^\d{2}:\d{2}:\d{2}$/');
define('REGEX_TIMESTAMP',     '/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/');
*/

/**
 * @package Framework
 */
class app_base_RuleValidator
{

	/**
	 * Validates that the validation rules are in the correct format.
     * The list of available option includes:
     * <ul>
     *   <li>$rules['type'] -> boolean
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> date in the format YYYY-MM-DD
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> datetime: in the format YYYY-MM-DD HH:MM:SS
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> float
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *       <li>['max'] -> float: the maximum value</li>
     *       <li>['min'] -> float: the mimnium value</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> integer
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *       <li>['max'] -> integer: the maximum value</li>
     *       <li>['min'] -> integer: the minimum value</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> object
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> text:
     *     <ul>
     *       <li>['alias'] -> text: The human-friendly field name</li>
     *       <li>['mandatory'] -> boolean: Whether the field must be supplied. Cannot be null or empty string.</li>
     *       <li>['max_length'] -> integer: maximum string length</li>
     *       <li>['min_length'] -> integer: minimum string length</li>
     *     </ul>
     *   </li>
     *   <li>$rules['type'] -> timestamp: determines if ssl should be used for connections</li>
     *   <li>$rules['in_array'] -> array: ...</li>
     *   <li>To do...</li>
     * </ul>
	 * @param array $rules associative array defining the validation rules for a particular field.
	 * @throws Exception
	 */
	public static function parseRules($rules)
	{
		if (!isset($rules['type']))
		{
			throw new Exception('A type must be defined');
		}
		
		if (!in_array($rules['type'], array('text', 'date', 'datetime', 'timestamp', 'boolean', 'object', 'integer', 'float')))
		{
			throw new Exception('Invalid data type defined: ' . $rules['type']);
		}
	}


	/**
	 * @param mixed $value the value being passed in
	 * @param array $rules associative array defining field validation rules
	 */
	public static function validate($value, $rules)
	{
//		echo "<p><b>app_base_RuleValidator::validate($value, $rules)</b></p>";
//		echo '<pre>';
//		print_r($rules);
//		echo '<pre>';
		self::parseRules($rules);
		
		$errors = null;

		if (!$value)
		{
			if (isset($rules['mandatory']) && $rules['mandatory'])
			{
				$errors[] = $rules['alias'] . ' must be supplied.';
			}
		}
		else
		{
			
//			$value = $request->getProperty($key);
			/*
			 * Handle checking for a value being one of set of values.
			 */
			if (isset($rules['in_array']))
			{
				if (isset($rules['type']) && $rules['type'] == 'object')
				{
					if (!in_array($value->getId(), $rules['in_array']))
					{
						$errors[] = $rules['alias'] . ' must be valid.';
					}
				}
				elseif (!in_array($value, $rules['in_array']))
				{
					$errors[] = $rules['alias'] . ' must be valid.';
				}
			}

			/*
			 * Handle checks for text
			 * Includes min_length, max_length
			 */
			if (isset($rules['type']))
			{
				if ($rules['type'] == 'object')
				{
//					echo "here";
//					$errors[] = $spec['alias'] . ' is an object.';
				}
				if ($rules['type'] == 'text')
				{
					if (isset($rules['min_length']) && $rules['min_length'] && strlen($value) < $rules['min_length'])
					{
						$errors[] = $rules['alias'] . ' must be at least ' . $rules['min_length'] . ' characters long.';
					}
					if (isset($rules['max_length']) && isset($rules['max_length']) && strlen($value) > $rules['max_length'])
					{
						$errors[] = $rules['alias'] . ' must be a maximum of ' . $rules['max_length'] . ' characters long.';
					}
//					continue;
				}
			
				// Handle checks for boolean
				if ($rules['type'] == 'boolean')
				{
					// TODO - anything needed here?
				}

				/*
				 * Handle checks for integer / float.
				 * Includes min, max
				 */
				if ($rules['type'] == 'integer' || $rules['type'] == 'float')
				{
					if (isset($rules['min']) && $value < $rules['min'])
					{
						$errors[] = $rules['alias'] . ' must be greater than or equal to ' . $rules['min'] . '.';
					}
					elseif (isset($rules['max']) && $value > $rules['max'])
					{
						$errors[] = $rules['alias'] . ' must be less than or equal to ' . $rules['max'] . '.';
					}
//					continue;
				}
				
				/*
				 * Handle checks for date.
				 * YYYY-MM-DD
				 */
				if ($rules['type'] == 'date')
				{
					if (!preg_match(REGEX_DATE, $value))
					{
						$errors[] = $rules['alias'] . ' must be of the form YYYY-MM-DD.';
					}
					elseif (!self::isValidDate($value))
					{
						$errors[] = $rules['alias'] . ' must be a valid date.';
					}
//					continue;
				}
			
				/*
				 * Handle checks for time.
				 * HH:MM:SS
				 */
				if ($rules['type'] == 'time')
				{
					if (!preg_match(REGEX_TIME, $value))
					{
						$errors[] = $rules['alias'] . ' must be of the form HH:MM:SS.';
					}
//					continue;
				}
			
				/*
				 * Handle checks for datetime / timestamp.
				 * YYYY-MM-DD HH:MM:SS
				 */
				if ($rules['type'] == 'datetime' || $rules['type'] == 'timestamp')
				{
					if (!preg_match(REGEX_TIMESTAMP, $value))
					{
						$errors[] = $rules['alias'] . ' must be of the form YYYY-MM-DD HH:MM:SS.';
					}
					elseif (!self::isValidDate($value))
					{
						$errors[] = $rules['alias'] . ' must be a valid date.';
					}
//					continue;
				}
			}
		}
		
		if (count($errors) > 0)
		{
			$errs = array();
			foreach ($errors as $error)
			{
				$errs[] = new app_base_ValidationError($error);  
			}
			return $errs[0];
		}
		return;
	}

	/**
	 * Validates a Gregorian date.
	 * @param string $data the date as a string in the form 
	 *        'YYYY-MM-DD HH:MM:SS' or 'YYYY-MM-DD'. 
	 * @return boolean true if valid; false if invalid
	 */
	protected static function isValidDate($date)
	{
		if (preg_match(REGEX_TIMESTAMP, $date))
		{
			// string format YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
			$year  = (int)substr($date, 0, 4);
			$month = (int)substr($date, 5, 2);
			$day   = (int)substr($date, 8, 2);
			return checkdate($month, $day, $year);
		}
		if (preg_match(REGEX_DATE, $date))
		{
			// string format DD/MM/YYYY
			$year  = (int)substr($date, 6, 4);
			$month = (int)substr($date, 3, 2);
			$day   = (int)substr($date, 0, 2);
			return checkdate($month, $day, $year);
		}
		return false;
	}

}

?>