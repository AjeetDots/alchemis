<?php

//require_once('include/Utils/RegularExpression.class.php');
require_once('RegularExpression.class.php');



//
// Define constants
//
if (!defined('BYTES_IN_GIGABYTE'))
{
	define('BYTES_IN_GIGABYTE', (float)1073741824);
}

if (!defined('BYTES_IN_MEGABYTE'))
{
	define('BYTES_IN_MEGABYTE', (float)1048576);
}

if (!defined('BYTES_IN_KILOBYTE'))
{
	define('BYTES_IN_KILOBYTE', (float)1024);
}


/**
 * Utils Class<br />
 * Provides useful utility functions.
 * @access public
 * @package myPRM.net
 */
class Utils
{

	/**
	 * Removes the item at the given index from the array.<br />
	 *
	 * E.g. $my_array = ("a", "b", "c", "d", "e");<br />
	 *      array_remove($my_array, 2) = array("a", "b", "d", "e");
	 *
	 * @param array the array top remove the element from
	 * @param integer index of the element to remove
	 * @return array the resulting array with the element removed
	 * @access public
	 * @static
	 */
	public static function arrayRemove($array, $index)
	{
		$new_array = $array;
		$a = array_slice($array, 0, $index);
		$b = array_slice($array, $index + 1);
		return array_merge($a, $b);
	}


	/**
	 * Returns the current local datetime in the form 'YYYY-MM-DD HH:MM:SS', as required by MySQL.
	 * @return string the current datetime in the form 'YYYY-MM-DD HH:MM:SS'.
	 * @access public
	 * @static
	 */
	public static function getTimestamp()
	{
		return date('Y-m-d H:i:s');
	}


	/**
	 * Checks to see if the date is a valid date.
	 * @param string the day of the month, 'dd'
	 * @param string the month of the year, 'mm'
	 * @param string the year, 'yy' or 'yyyy'
	 * @return boolean
	 * @access private
	 * @static
	 * @see is_date()
	 */
	public static function _date_is_valid($day, $month, $year)
	{
		if (strlen($year) == 2)
		{
			$year = '20'. $year;
		}
		elseif (strlen($year) == 3)
		{
			$year = '2'. $year;
		}

		$month_length = array (0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if (Utils::_is_leap_year($year))
		{
			// 29 days in February in a leap year (including year 2000)
			$month_length[2] = 29;
		}

		$month = intval($month);

		if ($month < 1 || $month > 12)
		{
			return false;
		}
		elseif ($day < 1 || $day > $month_length[$month])
		{
			return false;
		}
		else
		{
			return true;
		}
	}


	/**
	 * Checks to see if the year is a leap year.
	 * @param integer 4-digit year
	 * @return boolean true if the year is a leap year; false otherwise
	 * @access public
	 * @static
	 * @see is_date()
	 */
	public static function _is_leap_year($year)
	{
		if (($year % 4) == 0)
		{
			// If year is divisible by 4, therefore a leap year
			return true;
		}
		elseif (($year % 400) == 0)
		{
			// If year is divisible by 400, therefore a leap year
			return true;
		}
		elseif (($year % 100) == 0)
		{
			// If year is divisible by 100, therefore a leap year
		    return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Checks a string to see that it is of the correct format for use by MySQL, ie 'YYYY-MM-DD'.
	 * @param string the date as a string in the form 'YYYY-MM-DD'
	 * @return boolean true if successful; false otherwise
	 */
	public static function isValidDate($date)
	{
		if (preg_match(REGEX_MYSQL_DATE, $date) || preg_match(REGEX_MYSQL_DATETIME, $date))
		{
			// string format '1981-04-06'
			$year  = substr($date, 0, 4);
			$month = substr($date, 5, 2);
			$day   = substr($date, 8, 2);
			return self::_date_is_valid($day, $month, $year);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Checks a string to see that it is of the correct format for use by MySQL, ie 'YYYY-MM-DD'.
	 * @param string the date as a string in the form 'YYYYMM'
	 * @return boolean true if successful; false otherwise
	 */
	public static function isValidYearMonth($year_month)
	{
		if (preg_match(REGEX_YEAR_MONTH, $year_month))
		{
			// string format '198104'
			$year  = substr($year_month, 0, 4);
			$month = substr($year_month, 4, 2);
			return self::_date_is_valid(1, $month, $year);
		}
		else
		{
			return false;
		}
	}


	/**
	 * Checks a date string to see if it is in the past.
	 * @param string $date - the date as a string in the form 'YYYY-MM-DD'
	 * @return boolean true if in the past; false otherwise.
	 * @access public
	 * @static
	 */
	function isInThePastDate($date)
	{
		if (isValidDate($date))
		{
			return $date < date('Y-md');
		}
		else
		{
			return false;
		}
	}


	/**
	 * Checks a string to see that it is of the correct format for use by MySQL, ie 'YYYY-MM-DD HH:MM:SS'.
	 * @param string the date as a string in the form 'YYYY-MM-DD HH:MM:SS'
	 * @return boolean true if successful; false otherwise.
	 * @access public
	 * @static
	 */
	function isValidDatetime($datetime)
	{
		if (preg_match(REGEX_MYSQL_DATETIME, $datetime))
		{
			// string format '1981-04-06 09:00:00'
			$year   = substr($datetime,  0, 4);
			$month  = substr($datetime,  5, 2);
			$day    = substr($datetime,  8, 2);
			$hour   = substr($datetime, 11, 2);
			$minute = substr($datetime, 14, 2);
			$second = substr($datetime, 17, 2);

			return checkdate($month, $day, $year) && Utils::checktime($hour, $minute, $second);
		}
		else
		{
			return false;
		}
	}


	/**
	 * Checks that a time is valid.
	 * @param integer the hour
	 * @param integer the minute
	 * @param integer the second
	 * @return boolean true if successful; false otherwise.
	 * @access public
	 * @static
	 */
	function checktime($hour, $minute, $second)
	{
		if ( !is_int($hour) || ($hour < 0 && $hour > 23) )
		{
			return false;
		}

		if ( !is_int($minute) || ($minute < 0 && $minute > 59) )
		{
			return false;
		}

		if ( !is_int($second) || ($second < 0 && $second > 59) )
		{
			return false;
		}

		// If got here then successful
		return true;
	}


	/**
	 * Converts a MySQL timestamp string to a more appropriate data string.<br />
	 * E.g. cTimestampToString('20040105') = '05/01/2004'<br/>
	 *      cTimestampToString('20040105154024') = '05/01/2004 15:40:24'
	 * @param string the MySQL timestamp in the form 'YYYYMMDDHHMMSS'.
	 * @return string the date in the form 'DD/MM/YYYY HH:MM:SS'.
	 * @access public
	 * @static
	 */
	function cTimestampToString($timestamp)
	{
		// string format YYYYMMDDHHMMSS
		$year   = substr($timestamp, 0, 4);
		$month  = substr($timestamp, 4, 2);
		$day    = substr($timestamp, 6, 2);
		$hour   = substr($timestamp, 8, 2);
		$minute = substr($timestamp, 10, 2);
		$second = substr($timestamp, 12, 2);

		if (($hour == '00') && ($minute == "00") && ($second == "00"))
		{
			return $day.'/'.$month.'/'.$year;
		}
		else
		{
			return $day.'/'.$month.'/'.$year.' '.$hour.':'.$minute.':'.$second;
		}
	}


	/**
	 * Returns the current local datetime in the form 'YYYY-MM-DD HH:MM:SS', as required by MySQL.
	 * @param string the date
	 * @param string [optional|default: null] the input format
	 * @param string [optional|default: null] the output format
	 * @return string the current datetime.
	 * @access public
	 * @static
	 */
	function DateFormat($date, $inputFormat=null, $outputFormat=null)
	{
		if (is_null($date) || empty($date) || $date == '0000-00-00' || $date == '--')
			return;

		if (!is_null($inputFormat) && !is_null($outputFormat))
		{
			// Initialise to default values.
			$year    = '1970';
			$month   = '01';
			$day     = '01';
			$hours   = '00';
			$minutes = '00';
			$seconds = '00';

			switch ($inputFormat)
			{
				case 'YYYYMMDDHHMMSS':
					$year    = substr($date, 0, 4);
					$month   = substr($date, 4, 2);
					$day     = substr($date, 6, 2);
					$hours   = substr($date, 8, 2);
					$minutes = substr($date, 10, 2);
					$seconds = substr($date, 12, 2);
					break;

				case 'YYYY-MM-DD HH:MM:SS':
					$year    = substr($date, 0, 4);
					$month   = substr($date, 5, 2);
					$day     = substr($date, 8, 2);
					$hours   = substr($date, 11, 2);
					$minutes = substr($date, 14, 2);
					$seconds = substr($date, 17, 2);
					break;

				case 'YYYY-MM-DD':
					$year   = substr($date, 0, 4);
					$month  = substr($date, 5, 2);
					$day    = substr($date, 8, 2);
					break;

				case 'DD/MM/YYYY':
					$year    = substr($date, 6, 4);
					$month   = substr($date, 3, 2);
					$day	 = substr($date, 0, 2);

					break;

				case 'DD/MM/YYYY HH:MM:SS':
					$year    = substr($date, 6, 4);
					$month   = substr($date, 3, 2);
					$day	 = substr($date, 0, 2);
					$hours   = substr($date, 11, 2);
					$minutes = substr($date, 14, 2);
					$seconds = substr($date, 17, 2);

					break;

				case 'YYYYMMDD':
					$year   = substr($date, 0, 4);
					$month  = substr($date, 4, 2);
					$day    = substr($date, 6, 2);
					break;

				default:
					// -- nothing --
					break;
			}

			switch ($outputFormat)
			{
				case 'YYYY-MM-DD HH:MM:SS':
					return $year.'-'.$month.'-'.$day.' '.$hours.':'.$minutes.':'.$seconds;

				case 'YYYYMMDD':
					return $year.$month.$day;

				case 'DD-MM-YYYY':
					return $day.'-'.$month.'-'.$year;

				case 'DD/MM/YYYY':
					return $day.'/'.$month.'/'.$year;

				case 'DD/MM/YYYY HH:MM:SS':
					return $day.'/'.$month.'/'.$year.' '.$hours.':'.$minutes.':'.$seconds;

				case "D MMM YY":
					return ltrim($day, '0').' '.Utils::getMonthName($month).' '.substr($year, 2, 2);

				case "D MMM YYYY":
					return ltrim($day, '0').' '.Utils::getMonthName($month).' '.$year;

				case "D-MMM-YY":
					return ltrim($day, '0').'-'.Utils::getMonthName($month).'-'.substr($year, 2, 2);

				case "D-MMM-YYYY":
					return ltrim($day, '0').'-'.Utils::getMonthName($month).'-'.$year;

				case "D MMM YY HH:MM:SS":
					return ltrim($day, '0').' '.Utils::getMonthName($month).' '.substr($year, 2, 2).' '.$hours.':'.$minutes.':'.$seconds;

				case "HH:MM:SS D MMM YY":
					return $hours.':'.$minutes.':'.$seconds.' '.ltrim($day, '0').' '.Utils::getMonthName($month).' '.substr($year, 2, 2);

				case "D MMM YY HH:MM":
					return ltrim($day, '0').' '.Utils::getMonthName($month).' '.substr($year, 2, 2).' '.$hours.':'.$minutes;

				case "HH:MM D MMM YY":
					return $hours.':'.$minutes.' '.ltrim($day, '0').' '.Utils::getMonthName($month).' '.substr($year, 2, 2);

				case "D-MMM-YY HH:MM:SS":
					return ltrim($day, '0').'-'.Utils::getMonthName($month).'-'.substr($year, 2, 2).' '.$hours.':'.$minutes.':'.$seconds;

				case "D-MMM-YY HH:MM":
					return ltrim($day, '0').'-'.Utils::getMonthName($month).'-'.substr($year, 2, 2).' '.$hours.':'.$minutes;

				case 'DD-MM-YYYY HH:MM:SS':
					return $day.'-'.$month.'-'.$year.' '.$hours.':'.$minutes.':'.$seconds;

				case 'YYYY-MM-DD':  // fall-through
				default:
					return $year.'-'.$month.'-'.$day;
			}
		}
		else
		{
			// string format YYYY-MM-DD
			$year   = substr($date, 0, 4);
			$month  = substr($date, 5, 2);
			$day    = substr($date, 8, 2);

			return $day.'/'.$month.'/'.$year;
		}
	}


	/**
	 * Checks whether a given string value is an integer string.<br />
	 * E.g. isInteger("12345")  == true<br />
	 *      isInteger("123.45") == false<br />
	 *      isInteger("test")   == false
	 * @param string input value to test
	 * @return boolean true if input is an integer, false otherwise.
	 * @access public
	 * @static
	 */
	function isInteger($input)
	{
		return (preg_match(REGEX_INTEGER, $input));
	}


	/**
	 * Checks whether a given string value is an integer string.<br />
	 * E.g. isInteger("12345")  == true<br />
	 *      isInteger("123.45") == false<br />
	 *      isInteger("test")   == false
	 * @param integer the numeric month
	 * @param integer [default: 0] the mode to use
	 * @return string the month as a string if successful; false otherwise
	 * @access public
	 * @static
	 */
	function getMonthName($month, $mode=0)
	{
		$month == (int)$month;

		if ($mode == 1)
		{
			switch ($month)
			{
				case 1:
					return 'January';
				case 2:
					return 'February';
				case 3:
					return 'March';
				case 4:
					return 'April';
				case 5:
					return 'May';
				case 6:
					return 'June';
				case 7:
					return 'July';
				case 8:
					return 'August';
				case 9:
					return 'September';
				case 10:
					return 'October';
				case 11:
					return 'November';
				case 12:
					return 'December';
				default:
					return false;
			}
		}
		else
		{
			switch ($month)
			{
				case 1:
					return 'Jan';
				case 2:
					return 'Feb';
				case 3:
					return 'Mar';
				case 4:
					return 'Apr';
				case 5:
					return 'May';
				case 6:
					return 'Jun';
				case 7:
					return 'Jul';
				case 8:
					return 'Aug';
				case 9:
					return 'Sep';
				case 10:
					return 'Oct';
				case 11:
					return 'Nov';
				case 12:
					return 'Dec';
				default:
					return false;
			}
		}
	}


	/**
	 * Converts bytes into an alternative unit of measurement. Supported units are in the set
	 * {'KB', 'MB', 'GB'}.
	 * @param float the bytes value to convert
	 * @param string the unit to convert to
	 * @param integer the precision to which to round the return value
	 * @return float the bytes in an alternative unit of measurement.
	 * @access public
	 * @static
	 */
	function convertBytes($bytes, $unit, $precision = 0)
	{
		if (is_null($unit) || empty($unit))
		{
			if ($bytes >= BYTES_IN_GIGABYTE)
				$unit = 'GB';
			elseif ($bytes >= BYTES_IN_MEGABYTE)
				$unit = 'MB';
			elseif ($bytes >= BYTES_IN_KILOBYTE)
				$unit = 'KB';
			else
				$unit = 'B';
		}

		$unit = strtoupper($unit);

		switch ($unit)
		{
			case 'GB':
				$size = $bytes / BYTES_IN_GIGABYTE;
				return round($size, $precision) . ' GB';

			case 'MB':
				$size = $bytes / BYTES_IN_MEGABYTE;
				return round($size, $precision) . ' MB';

			case 'KB':
				$size = $bytes / BYTES_IN_KILOBYTE;
				return round($size, $precision) . ' KB';

			case 'B':  // fall through
			default:
				return $bytes . ' B';
		}
	}


	/**
	 * Returns the given date from the page.
	 * @param string the name of the date form field to return
	 * @return string the date as a string the format 'YYYY-MM-DD' if successful; '0000-00-00' otherwise
	 * @access public
	 * @static
	 */
	function getFormDate($field)
	{
		$day   = $field.'_Day';
		$month = $field.'_Month';
		$year  = $field.'_Year';

		if ( isset($_POST[$day]) || isset($_POST[$month]) || isset($_POST[$year]) )
		{
			$day   = (strlen($_POST[$day])   != 2) ? '00'   : $_POST[$day];
			$month = (strlen($_POST[$month]) != 2) ? '00'   : $_POST[$month];
			$year  = (strlen($_POST[$year])  != 4) ? '0000' : $_POST[$year];
			return $year.'-'.$month.'-'.$day;
		}
		else
		{
			return '0000-00-00';
		}
	}


	/**
	 * Returns the given time from the page.
	 * @param string the name of the time form field to return
	 * @return string the selected time as a string the format 'HH:MM:SS' if successful; '00:00:00' otherwise
	 * @access public
	 * @static
	 */
	function getFormTimeSmarty($field)
	{
		$hour   = $field.'_Hour';
		$minute = $field.'_Minute';

		if ( isset($_POST[$hour]) || isset($_POST[$minute]))
		{
			$hour   = (strlen($_POST[$hour])   != 2) ? '00' : $_POST[$hour];
			$minute = (strlen($_POST[$minute]) != 2) ? '00' : $_POST[$minute];
			return $hour.':'.$minute.':00';
		}
		else
		{
			return '00:00:00';
		}
	}

 	/** Returns the given time from the page.
	 * @param string the name of the time form field to return
	 * @return string the selected time as a string the format 'HH:MM:SS' if successful; '00:00:00' otherwise
	 * @access public
	 * @static
	 */
	function getFormTime($hour, $minute)
	{
//		$hour   = $field.'_Hour';
//		$minute = $field.'_Minute';

		if ( isset($hour) || isset($minute))
		{
			$hour   = (strlen($hour)   != 2) ? '00' : $hour;
			$minute = (strlen($minute) != 2) ? '00' : $minute;
			return $hour.':'.$minute.':00';
		}
		else
		{
			return '00:00:00';
		}
	}

	/**
	 * Returns the given datetime from the page.
	 * @param string the name of the datetime form field to return
	 * @return string the selected datetime as a string the format 'YYYY-MM-DD HH:MM:SS' if
	 * successful; '0000-00-00 00:00:00' otherwise
	 * @access public
	 * @static
	 */
	function getFormDateTime($field)
	{
		$hour  = $field.'_Hour';
		$min   = $field.'_Minute';
		$day   = $field.'_Day';
		$month = $field.'_Month';
		$year  = $field.'_Year';

		if ( isset($_POST[$min]) || isset($_POST[$hour]) || isset($_POST[$day]) || isset($_POST[$month]) || isset($_POST[$year]) )
		{
			$hour  = (strlen($_POST[$hour])  != 2) ? '00'   : $_POST[$hour];
			$min   = (strlen($_POST[$min])   != 2) ? '00'   : $_POST[$min];
			$day   = (strlen($_POST[$day])   != 2) ? '00'   : $_POST[$day];
			$month = (strlen($_POST[$month]) != 2) ? '00'   : $_POST[$month];
			$year  = (strlen($_POST[$year])  != 4) ? '0000' : $_POST[$year];
			$date  = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';

			if ($date == '-- ::')
				return '0000-00-00 00:00:00';
			else
				return $date;
		}
		else
		{
			return '0000-00-00 00:00:00';
		}
	}


	/**
	 * Returns the difference between two dates.  Taken from the php.net manual forum under
	 * 'Date and Time Fiunctions' on 10th March 2005.  NOTE: Both date parameters MUST be supplied
	 * in the same format.
	 * @param integer the type of time interval to return ('s' = seconds, 'n' = minutes, etc - see
	 *                function body)
	 * @param integer the first date
	 * @param integer the second date
	 * @return integer number of 'intervals' between the two date parameters. If either or both
	 *                 dates are invalid then returns false.
	 * @access public
	 * @static
	 */
	public static function dateDiff($interval, $dateTimeBegin, $dateTimeEnd)
	{
//		echo '$dateTimeBegin = ' . $dateTimeBegin . '<br />';
		$dateTimeBegin = strtotime($dateTimeBegin);
//		echo '$dateTimeBegin = ' . $dateTimeBegin . '<br />';

		if ($dateTimeBegin === -1)
		{
			return false;
		}

//		echo '$dateTimeEnd = ' . $dateTimeEnd . '<br />';
		$dateTimeEnd = strtotime($dateTimeEnd);
//		echo '$dateTimeBegin = ' . $dateTimeBegin . '<br />';

		if ($dateTimeEnd === -1)
		{
			return false;
		}

		$dif = $dateTimeEnd - $dateTimeBegin;

//		echo '$dif = ' . $dif . '<br />';

		switch ($interval)
		{
			case 's':  // seconds
				return $dif;

			case 'n':  // minutes
				return floor($dif / 60);  // 60s = 1m

			case 'h':  // hours
				return floor($dif / 3600);  // 3600s = 1h

			case 'd':  // days
				return floor($dif / 86400);  // 86400s = 1d

			case 'ww':  //week
				return floor($dif / 604800);  // 604800s = 1week = 1semana

			case 'm':  // similar result "m" dateDiff Microsoft
				$monthBegin = (date('Y', $dateTimeBegin) * 12) + date('n', $dateTimeBegin);
//				echo '$monthBegin = ' . $monthBegin . '<br />';
				$monthEnd = (date('Y', $dateTimeEnd) * 12) + date('n', $dateTimeEnd);
//				echo '$monthEnd	 = ' . $monthEnd . '<br />';
				$monthDiff = $monthEnd - $monthBegin;
				return $monthDiff;
			case 'yyyy': //similar result "yyyy" dateDiff Microsoft
				return(date('Y', $dateTimeEnd) - date('Y', $dateTimeBegin));

			default:
				return floor($dif/86400);  // 86400s = 1d
		}
	}


	/**
	 * @name dateAdd()
	 * @author   J de Silva
	 * @created  February 21, 2003
	 * @modified July 30, 2004
	 * Adds
	 * @param string the date
	 * @param string the interval unit to use (e.g. 'month', 'day').  Defaults to 'year'.
	 * @param integer the amount to add
	 * @param string the return date format
	 * @return string a string formatted according to the given format string if a valid date is
	 *                given; boolean false otherwise.
	 * @access public
	 * @static
	 */
	public static function dateAdd($date, $interval, $add, $format = 'Y-m-d')
	{
		$date = strtotime($date);

		if ($date !== -1)
		{
			$date = getdate($date);
			//echo"<pre>".print_r($date)."</pre>";

			switch (strtolower($interval))
			{
				case 'year':
					$date['year'] += $add;
					break;

				case 'month':
					$date['mon'] += $add;
					break;

				case 'day':
					$date['mday'] += $add;
					break;

				case 'hours':
					$date['hours'] += $add;
					break;

				case 'minutes':
					$date['minutes'] += $add;
					break;

				case 'seconds':
					$date['seconds'] += $add;
					break;
			}

			return date($format, mktime($date['hours'], $date['minutes'], $date['seconds'], $date['mon'], $date['mday'], $date['year']));
		}


		return false;
	}

	/**
		* @param string date_time
	 	* @return string a string showing the amount of lapsed time since input param
	 	* @access public
	 	* @static
	 */
	public static function elapsedTime($date_time)
	{
        $ts = time() - strtotime(str_replace("	-","/",$date_time));

        if($ts>31536000) $val = round($ts/31536000,0).' year';
        else if($ts>2419200) $val = round($ts/2419200,0).' month';
        else if($ts>604800) $val = round($ts/604800,0).' week';
        else if($ts>86400) $val = round($ts/86400,0).' day';
        else if($ts>3600) $val = round($ts/3600,0).' hour';
        else if($ts>60) $val = round($ts/60,0).' minute';
        else $val = $ts.' second';

        if($val>1) $val .= 's';
        return $val;
    }

	/**
	 * Goes through a multidimensional array, and sorts it by the desired key
	 * (defaulting to 'id'). The 'id' index can start at any point, and any
	 * array item missing the id index will be added to the end.
	 *
	 * <code>
	 * So, for example, if you have an array like:
	 * array[0]['value'] = "statement 2"
	 * array[0]['id'] = "2"
	 * array[1]['value'] = "statement 3"
	 * array[1]['id'] = "3"
	 * array[2]['value'] = "statement 1"
	 * array[2]['id'] = "1"
	 *
	 * it would rearrange and return the array to be like:
	 *
	 * array[0]['value'] = "statement 1"
	 * array[0]['id'] = "1"
	 * array[1]['value'] = "statement 2"
	 * array[1]['id'] = "2"
	 * array[2]['value'] = "statement 3"
	 * array[2]['id'] = "3"
	 * </code>
	 *
	 * @param array $array the multidimensional array to sort
	 * @param string $id the key to sort on
	 * @param boolean $reverse whether to reverse the results (.e.g for dates)
	 * @return array the sorted multidimensional array
	 */
	public static function msort($array, $id = 'id', $reverse = false)
	{
		$temp_array = array();
		while(count($array) > 0)
		{
			$lowest_id = 0;
			$index = 0;
			foreach ($array as $item)
			{
				if (isset($item[$id]) && $array[$lowest_id][$id])
				{
					if ($item[$id]<$array[$lowest_id][$id])
					{
						$lowest_id = $index;
					}
				}
				$index++;
			}
			$temp_array[] = $array[$lowest_id];
			$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
		}
		if ($reverse)
        {
        	$temp_array = array_reverse($temp_array);
        }
        return $temp_array;
    }


    protected static function getBankHolidays()
    {
        $bank_holidays = array( '2008-12-25',
                                '2008-12-26',
                                '2010-01-01',
                                '2010-04-02',
                                '2010-04-05',
                                '2010-05-03',
                                '2010-05-31',
                                '2010-08-30',
                                '2010-12-27',
                                '2010-12-28',
        );
        return $bank_holidays;
    }

	/**
	 * TODO
	 *  - needs to also eliminate bank hoidays
	 * Return the number of working days between two dates.
	 * @param string $start_date in format 'YYYY-MM-DD'
	 * @param string $end_date in format 'YYYY-MM-DD'
	 * @return integer
	 */
	public static function getWorkingDays($start_date, $end_date)
	{
//		echo $start_date;
//		echo $end_date;
		$bank_holidays = self::getBankHolidays();
		$working_days = 0;
		$date = $start_date;
		while ($date <= $end_date)
		{
			if (!in_array($date, $bank_holidays)) {
				$day_of_week = date('N', strtotime($start_date));
				if ($day_of_week >= 1 && date('N', strtotime($date)) <= 5)
				{
					$working_days++;
				}
			}

			// Increment the date by 1 day
			$year  = date('Y', strtotime($date));
			$month = date('m', strtotime($date));
			$day   = date('d', strtotime($date));
			$date  = date('Y-m-d', mktime(0, 0, 0, $month, $day + 1, $year));

		}

		// TODO
		//  - Also need to take off bank holidays in this month
		return $working_days;
	}

	/**
	 * Return the number of working days in a month.
	 * @param string $year_month in the format 'YYYYMM'
	 * @see getWorkingDays()
	 * @return integer
	 */
	public static function getWorkingDaysInMonth($year_month)
	{
		$year  = substr($year_month, 0, 4);
		$month = substr($year_month, 4, 2);
		$start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		$end   = date('Y-m-d', mktime(0, 0, 0, $month + 1, 0, $year));
		return self::getWorkingDays($start, $end);
	}

}


?>