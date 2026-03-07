<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_radios} function plugin
 *
 * File:       function.html_radios.php<br />
 * Type:       function<br />
 * Name:       html_radios<br />
 * Date:       24.Feb.2003<br />
 * Purpose:    Prints out a list of radio input types<br />
 * Input:<br>
 *           - name       (optional) - string default "radio"
 *           - values     (required) - array
 *           - options    (optional) - associative array
 *           - checked    (optional) - array default not set
 *           - separator  (optional) - ie <br> or &nbsp;
 *           - output     (optional) - the output next to each radio button
 *           - assign     (optional) - assign the output as an array to this variable
 * Examples:
 * <pre>
 * {html_radios values=$ids output=$names}
 * {html_radios values=$ids name='box' separator='<br>' output=$names}
 * {html_radios values=$ids checked=$checked separator='<br>' output=$names}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.html.radios.php {html_radios}
 *      (Smarty online manual)
 * @author     Christopher Kvarme <christopher.kvarme@flashjab.com>
 * @author credits to Monte Ohrt <monte at ohrt dot com>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */ 
/* 
 * Smarty plugin 
 * ------------------------------------------------------------- 
 * Type:     function 
 * Name:     month_calendar 
 * Version:  1.0 
 * Date:     Nov 3, 2003 
 * Author:    Goran Pilipovic fka bluesman <bluesman at eunet dot yu>
 *           (Modified from http://www.phpinsider.com/smarty-forum/viewtopic.php?t=1497) 
 * Purpose:  output table formated calendar for given month 
 * Input:    year             = int,    year 
 *           month           = int,    month from 1 to 12 
 *           year_month      - string YYYY-MM overrides any year and month setting
 *           url             = string, if you want to create link on each day, just enter url, eg "gotodate.php" 
 *           url_var         = string, the name to add to query string, eg date -> gotodate.php?date=YYY-mm-dd 
 *           assign          = bool,   assigns to template var instead of printed. 
 *           width           = int,    table width in pixels 
 *           no_future         = bool,   if set, dates in future are not linked 
 *           print_month_name   = bool,   if the month name should be printed
 *           navigation_links = bool  whether to display navigation to previous and next months
 * default:  current month with no links, printed out 
 * todo:     - Set first day of the week, currently it's sunday, but I'd like it to be custom 
 *           - custom date format as parameter, currently it's YYYY-mm-dd 
 *           - custom day names 
 *           - custom month names 
 * 
 * Examples: {month_calendar year="2003" month="11" url="home.php" assign="some_var"}
 *  
 * ------------------------------------------------------------- 
 */

if (!defined('SMARTY_PLUGINS_DIR')) {
    define('SMARTY_PLUGINS_DIR', SMARTY_DIR . 'plugins' . DIRECTORY_SEPARATOR);
}


require_once(SMARTY_PLUGINS_DIR . 'function.popup.php');


function smarty_function_calendar_mini_month($params, &$smarty)
{
	static $cycle_vars;
	
	extract($params);
	
	if (!isset($navigation))
	{
		$navigation = false;
	}

	if (empty($header))
	{
		$header = true;
	}
	
	if (empty($year))
	{
		$year = intval(date('Y'));
	}
	else
	{
		$year = intval($year);
	}
	
	if (empty($month))
	{
		$month = intval(date('n'));
	}
	else
	{
		$month = intval($month);
	}
	
	if (!empty($year_month))
	{
		if (preg_match('/^\d{4}-\d{2}/', $year_month))
		{
			$year  = intval(substr($year_month, 0, 4));
			$month = intval(substr($year_month, 5, 2));
		} 
	}
	
	if (empty($url_var))
	{
		$url_var = "date";
	}
	
	if (empty($bgcolor))
	{
		$bgcolor = "#EEEEEE";
	}
	
	if (empty($m_bgcolor))
		$m_bgcolor = "#FFFFFF";

	if (empty($selected))
	{
		// If no other date is supplied, and we're displaying the current month, then highlight today
		$today = date('Y-m', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		$tmp = date('Y-m', mktime(0, 0, 0, $month, 1, $year));
		if ($today == $tmp)
		{
			$selected = intval(date('d'));
		}
	}

	$colwidth = 0;
	if (!empty($width))
		$colwidth = round($width / 7);
	
	if (empty($no_future))
		$no_future = false;
	
	$implode_char = "&"; 
/*   if (!empty($url)) 
      { 
      if (!strpos ($url, "?")) 
         $implode_char = "?"; 
      } 
*/

	if (!checkdate ($month, 1, $year))
	{
		$smarty->trigger_error('month_calendar: invalid date');
		return;
	}
	
	$days = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
	$timestamp =  mktime (0, 0, 1, $month, 1, $year);
	$fist_dat_start = date("w", $timestamp);
	$days_in_month  = date("t", $timestamp);
	$today = date("Y-m-d");
	
	$retval = '';
//	if (!empty($print_month_name))
//	{
//		$retval .= date("F", $timestamp)." ". $year."<br>";
//	}
	
	$month_in_word = date('F'); 

	// Start table
	$retval .= '<table class="mainTable" cellspacing="1" cellpadding="0">' . "\n";
	
	// Print month name
	$retval .= '<tr>' . "\n";
	$retval .= '<td class="monthYearText monthYearRow" colspan="7">';
	$retval .= date('F Y', mktime(0, 0, 0, $month, 1, $year));
	$retval .= '</td>' . "\n";
	$retval .= '</tr>' . "\n";

	// Print day names
	$retval .= '<tr class="dayNamesText">' . "\n";
	foreach ($days as $day)
	{
		$retval .= '<td class="dayNamesRow" width="14%">' . $day . '</td>' . "\n";
	}
	$retval .= '</tr>' . "\n";
	
	
	// Print each row of days
	$retval .= '<tr class="rows">' . "\n";
	
/* Previous Month */
	 
	// Work out previous month
	$prev_month_timestamp =  mktime (0, 0, 1, $month-1, 1, $year);
	$days_in_prev_month = date('t', $prev_month_timestamp);
	
	// print all before first day start
	for ($i = $fist_dat_start-1; $i >= 0; $i--)
	{
		$day = $days_in_prev_month - $i;
		$retval .= '<td class="sOther">' . $day . '</td>' . "\n";
	}
	
	/* Current Month */
	// now print days
	for ($i = 1; $i <= $days_in_month; $i++)
	{
		if (($i + $fist_dat_start) % 7 == 1)
		{
			$retval .= "</tr>\n";
			$retval .= '<tr class="rows">' . "\n";
		} 

		if (isset($data[$i]))
		{
			$retval .= '<td class="events';
		}
		elseif (($i + $fist_dat_start) % 7 == 1 || ($i + $fist_dat_start) % 7 == 0)
		{
			$retval .= '<td class="s200';
		}
		else
		{
			$retval .= '<td class="s2';
		}
		
		// Check if today
		if ($selected == $i)
		{
			$retval .= ' today';
		}
		
		$retval .= '">';
		$retval .= $i;
//		$retval .= $i . '</td>';
		
		
//		$the_date = sprintf("%4d-%02d-%02d", $year, $month, $i);
//
//
//
//		if (isset($data[$i]))
//		{
//			foreach ($data[$i] as $d)
//			{
//				$ian_date = date('F j, Y', mktime(0, 0, 0, $month, $i, $year));
//				$ian_text = "<div class=&quot;popupEventTitle " . $d['type'] . "&quot;>" . $d['title'] . "</div><div class=&quot;popupEventTime&quot;>" . $d['start'] . '-' . $d['finish'] . "</div><div " .
//							"class=&quot;popupEventDescription&quot;>" . $d['notes'] . "</div>";
//				$popup_params = array('caption' => $ian_date, 'text' => $ian_text, 'capcolor' => "#00FFFF", 'bgcolor' => "#00FFFF");
//
//				$retval .= "\n"; 
////				$retval .= '<div class="titleTOC">' . "\n";
//				$retval .= '<div class="titleTOC" ' . smarty_function_popup($popup_params, $smarty) . '>';
//				$retval .= '<span class="' . $d['type']. '">&nbsp;&nbsp;</span>' . "\n";
//				$retval .= $d['start'] . '-' . $d['finish'] . ': ' . $d['title'] . "\n";
//				$retval .= '</div>' . "\n";
//
//
//			}
//		}
//
		$retval .= "</td>\n"; 
	} 

	// print what's left until the end of row
	$left_out = ($fist_dat_start + $days_in_month) % 7;
	if ($left_out > 0)
	{
		for ($i = 1; $i <= 7-$left_out; $i++)
		{
			$retval .= '<td class="sOther">' . $i . '</td>' . "\n";
		}
	}
	
	$retval .= "</tr>\n</table>\n"; 


	if ($navigation)
	{
		$retval .= '<table class="navTable" cellspacing="0" cellpadding="0">' . "\n";
		$retval .= '<tr>' . "\n";
		$retval .= '<td style="text-align: left; width: 50%"><a href="index.php?cmd=Calendar&mo=2&yr=2007" class="navTableText">Prev</a></td>' . "\n";
		$retval .= '<td style="text-align: right; width: 50%"><a href="demo.php?mo=4&amp;yr=2007#norm" class="navTableText">Next</a></td>' . "\n";
		$retval .= '</tr>' . "\n";
		$retval .= '</table>' . "\n";
	}
	
	// If assign to variable is set
	if (!empty($assign))
	{
		$smarty->assign($assign, $retval);
	}
	else
	{
		return $retval;
	}
} 

?>