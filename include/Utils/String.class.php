<?php

require_once('include/Utils/RegularExpression.class.php');

/**
 * String Class<br />
 * Provides functions relating to string.
 * @access public
 */
class C_String
{
	
	/**
	 * Formats a string ready for HTML. The htmlentities() function is first used to convert all 
	 * applicable characters to HTML entities. Then all newline characters ("\n") are replaced 
	 * with 'br tags' for correct display on screen.
	 * @param string the string to format.
	 * @return string the formatted string.
	 * @access public
	 */
	public static function htmlDisplay($string)
	{
		return str_replace("\n", "<br>", htmlentities($string));
	}
	
	
	/**
	 * Return�a�random�string.
	 * @author   Aidan�Lister�<aidan@php.net>
	 * @version  2.0
	 * @param��� integer  $length��Length�of�the�string�you�want�generated.
	 * @param��� string�� $seeds���The�seeds�you�want�the�string�to�be�generated�from.
	 */
	public static function randomString($length = 8, $seeds = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
	{
		$str = '';
		$seeds_count = strlen($seeds);
		
		//�Seed
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float)$usec * 100000);
		mt_srand($seed);
		
		// Generate
		for ($i = 0; $i < $length; $i++)
		{
			$str .= $seeds[mt_rand(0, $seeds_count - 1)];
		}
		
		return $str;
	}

	/**
	 * Truncate a string to a certain length if necessary, optionally splitting in the middle of a word, and
	 * appending the $etc string or inserting $etc into the middle.
	 * @param string
	 * @param integer
	 * @param string
	 * @param boolean
	 * @param boolean
	 * @return string
	 * @see smarty_modifier_truncate
	 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
	 *          truncate (Smarty online manual)
	 */
	public static function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
	{
	    if ($length == 0)
	        return '';
	
	    if (strlen($string) > $length) {
	        $length -= strlen($etc);
	        if (!$break_words && !$middle) {
	            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
	        }
	        if(!$middle) {
	            return substr($string, 0, $length).$etc;
	        } else {
	            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
	        }
	    } else {
	        return $string;
	    }
	}

	/**
	 * Format a name into firstname and first letter of surname.
	 * @param string the name
	 * @return string the formatted name
	 */
	public static function formatName($name)
	{
		$parts = explode(' ', $name);
		if (count($parts) == 2)
		{
			$formatted = $parts[0] . ' ' . substr($parts[1], 0, 1); 
			return $formatted;
		}
		else
		{
			return $name;
		}
	}

}


?>