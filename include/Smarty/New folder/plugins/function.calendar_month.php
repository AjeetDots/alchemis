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
 * Input:<br />
 *           - year                 (optional) - integer year
 *           - month                           - integer month from 1 to 12
 *           - day                  (optional) - integer the selected day
 *           - group                (optional) - boolean whether to group calendar items
 *           - navigation           (optional) - boolean whether to show navigation drop-down / links
 *           - legend               (optional) - boolean whether to show the colour-legend
 *           - hide_completed_items (optional) - whether to show completed items
 *           - nbm_id               (optional) - filter by NBM ID
 *           - client_id            (optional) - filter by client ID
 *           url                = string, if you want to create link on each day, just enter url, eg "gotodate.php"
 *           url_var            = string, the name to add to query string, eg date -> gotodate.php?date=YYY-mm-dd
 *           assign             = bool,   assigns to template var instead of printed.
 *           width              = int,    table width in pixels
 *           no_future          = bool,   if set, dates in future are not linked
 *           print_month_name   = bool,   if the month name should be printed
 *           start_day          = int,    numeric representation of the day of the week, 0 (for Sunday) through 6 (for Saturday)
 *                                        defaults to Monday
 *           day_format         = string, 'short' (eg Wed) or 'long' (eg Wednesday)
 * default:  current month with no links, printed out
 * todo:     - custom date format as parameter, currently it's YYYY-mm-dd
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

//define('SMARTY_FUNCTION_CALENDAR_MONTH_SUNDAY', 0);
//define("FOO2",    "something else");
//const $SMARTY_FUNCTION_CALENDAR_MONTH_SUNDAY = 0;

function smarty_function_calendar_month($params, &$smarty)
{
//	echo '<pre>';
//	print_r($params);
//	echo '</pre>';

	static $cycle_vars;

	extract($params);

	if (empty($data))
	{
		$data = array();
	}

	if (empty($nbm_id))
	{
		$nbm_id = null;
	}

	if (empty($client_id))
	{
		$client_id = null;
	}

//	echo "<pre>";
//	print_r($data);
//	echo "</pre>";


	if (empty($header))
	{
		$header = true;
	}

	if (empty($navigation))
	{
		$navigation = false;
	}

	if (empty($onclick))
	{
		$onclick = null;
	}

	// Year
	if (empty($year))
	{
		$year = intval(date('Y'));
	}
	else
	{
		$year = intval($year);
	}

	// Month
	if (empty($month))
	{
		$month = intval(date('n'));
	}
	else
	{
		$month = intval($month);
	}

	// Day
	if (!empty($day))
	{
		$highlighted_day = $day;
	}

	// Legend
	if (empty($legend))
	{
		$legend = false;
	}

	// Hide completed items
	if (empty($hide_completed_items))
	{
		$hide_completed_items = false;
	}

	if ($hide_completed_items)
	{
		$data = removeCompletedItems($data);
	}

	// url_var
	if (empty($url_var))
	{
		$url_var = "date";
	}

	if (empty($day_format))
	{
		$day_format = 'short';
	}

	if (!empty($day_link))
	{
//		echo "<p>BEFORE: \$day_link = $day_link</p>";
////'There are %d monkeys in the %s';
////		$day_link = printf($day_link, $year, $month, 1);
//		echo "<p>AFTER: \$day_link = $day_link</p>";
	}

	if (empty($bgcolor))
	{
		$bgcolor = "#EEEEEE";
	}

	if (empty($m_bgcolor))
		$m_bgcolor = "#FFFFFF";

	if (empty($selected))
		$selected = intval(date('d'));

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
	// Path to images
	$image_path = 'app/view/images/icons/';


	if (!isset($group))
	{
		$group = false;
	}

	if (!isset($start_day))
	{
		// Monday
		$start_day = 1;
	}

	if (!checkdate ($month, 1, $year))
	{
		$smarty->trigger_error('month_calendar: invalid date');
		return;
	}


	if ($start_day == 0)
	{
		// Sunday
		if ($day_format == 'long')
		{
			$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		}
		else
		{
			$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		}
	}
	else
	{
		// Monday
		if ($day_format == 'long')
		{
			$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		}
		else
		{
			$days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		}
	}

//	$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	// Get a timestamp representing the current month
	$timestamp =  mktime (0, 0, 1, $month, 1, $year);
//	echo "<p>" . date('Y-m-d H:i:s', $timestamp) . "</p>";

	// Numeric representation of the day of the week
	// 0 (for Sunday) through 6 (for Saturday)
	$fist_dat_start = date("w", $timestamp);


	if ($start_day == 0)
	{
		// Sunday
		$fist_dat_start = date("w", $timestamp);
	}
	elseif ($start_day == 1)
	{
		// Monday
		$fist_dat_start = date('w', $timestamp);
		if ($fist_dat_start > 0)
		{
			$fist_dat_start = $fist_dat_start - 1;
		}
		else
		{
			$fist_dat_start = 6;
		}
	}

	// Days in the current month
	$days_in_month  = date('t', $timestamp);

	// Set today's date for reference purposes
	$today = date('Y-m-d');

	$retval = "\n\n<!-- smarty_function_calendar_month -->";
//	if (!empty($print_month_name))
//	{
//		$retval .= date("F", $timestamp)." ". $year."<br>";
//	}


//	$retval .= '<table cellpadding="2" cellspacing="0" border="1" bgcolor="' . $bgcolor . '"';
	$retval .= "\n" . '<table class="mainTableTOC" cellspacing="1" cellpadding="0" border="0" style="width: 100%"';

	$month_in_word = date('F');

	if (!empty($width))
	{
		$retval .= ' width="' . $width . '"';
	}
	$retval .= ">\n";

	if ($header)
	{
		$retval .= "\t" . '<tr>' . "\n";
		$retval .= "\t\t" . '<td class="monthYearRowTOC" colspan="7">' . "\n";
		$retval .= "\t\t\t" . '<table width="100%">' . "\n";
		$retval .= "\t\t\t\t" . '<tr>' . "\n";
		$retval .= "\t\t\t\t\t" . '<td class="monthYearTextTOC">' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . '</td>' . "\n";
		$retval .= "\t\t\t\t\t" . '<td style="text-align: right;">';

		if ($navigation)
		{
			$retval .= '<input name="awioufhaioeu" type="button" id="awioufhaioeu" value="Today" class="formButtons" onClick="location=\'index.php?cmd=Calendar&amp;date=' . date('Y-m-d') . '&amp;nbm_id=' . $nbm_id . '&amp;client_id=' . $client_id . '\'" title="Go to ' . date('j F Y') . '" />' . "\n";

			$retval .= "<script>\n";
			$retval .= 'function goToSelectedDate()';
			$retval .= "{\n";
			$retval .= "\tvar year = document.getElementById('yr').value;\n";
			$retval .= "\tvar month = '00' + document.getElementById('mo').value;\n";
			$retval .= "\tmonth = month.substring(month.length - 2, month.length);\n";
			$retval .= "\tlocation = 'index.php?cmd=Calendar&date=' + year + '-' + month + '-01&nbm_id=" . $nbm_id . "&client_id=" . $client_id . "';\n";
			$retval .= "}\n";
			$retval .= "</script>\n";

			$retval .= '<select name="mo" class="formElements" id="mo">' . "\n";

			for ($i = 1; $i <= 12; $i++)
			{
				$month_name = date('F', mktime(0, 0, 0, $i, 1, 2000));
				if ($i == $month)
				{
					$retval .= '<option value="' . $i . '" selected="selected">' . $month_name . '</option>' . "\n";
				}
				else
				{
					$retval .= '<option value="' . $i . '">' . $month_name . '</option>' . "\n";
				}
	//			<option value="2">February</option><option value="3" SELECTED>March</option><option value='4'>April</option><option value='5'>May</option><option value='6'>June</option><option value='7'>July</option><option value='8'>August</option><option value='9'>September</option><option value='10'>October</option><option value='11'>November</option><option value='12'>December</option>';
			}
			$retval .= '</select>' . "\n";

			$retval .= '<select name="yr" class="formElements" id="yr">' . "\n";

			for ($i = 1970; $i <= 2100; $i++)
			{
				if ($i == $year)
				{
					$retval .= '<option selected="selected">' . $i . "</option>\n";
				}
				else
				{
					$retval .= "<option>$i</option>\n";
				}
			}

//			$retval .= '<option>1970</option><option>1971</option><option>1972</option><option>1973</option><option>1974</option><option>1975</option><option>1976</option><option>1977</option><option>1978</option><option>1979</option><option>1980</option><option>1981</option><option>1982</option><option>1983</option><option>1984</option><option>1985</option><option>1986</option><option>1987</option><option>1988</option><option>1989</option><option>1990</option><option>1991</option><option>1992</option><option>1993</option><option>1994</option><option>1995</option><option>1996</option><option>1997</option><option>1998</option><option>1999</option><option>2000</option><option>2001</option><option>2002</option><option>2003</option><option>2004</option><option>2005</option><option>2006</option><option SELECTED>2007</option><option>2008</option><option>2009</option><option>2010</option><option>2011</option><option>2012</option><option>2013</option><option>2014</option><option>2015</option><option>2016</option><option>2017</option><option>2018</option><option>2019</option><option>2020</option><option>2021</option><option>2022</option><option>2023</option><option>2024</option><option>2025</option><option>2026</option><option>2027</option><option>2028</option><option>2029</option><option>2030</option><option>2031</option><option>2032</option><option>2033</option><option>2034</option><option>2035</option><option>2036</option><option>2037</option><option>2038</option><option>2039</option><option>2040</option><option>2041</option><option>2042</option><option>2043</option><option>2044</option><option>2045</option><option>2046</option><option>2047</option><option>2048</option><option>2049</option><option>2050</option><option>2051</option><option>2052</option><option>2053</option><option>2054</option><option>2055</option><option>2056</option><option>2057</option><option>2058</option><option>2059</option><option>2060</option><option>2061</option><option>2062</option><option>2063</option><option>2064</option><option>2065</option><option>2066</option><option>2067</option><option>2068</option><option>2069</option><option>2070</option><option>2071</option><option>2072</option><option>2073</option><option>2074</option><option>2075</option><option>2076</option><option>2077</option><option>2078</option><option>2079</option><option>2080</option><option>2081</option><option>2082</option><option>2083</option><option>2084</option><option>2085</option><option>2086</option><option>2087</option><option>2088</option><option>2089</option><option>2090</option><option>2091</option><option>2092</option><option>2093</option><option>2094</option><option>2095</option><option>2096</option><option>2097</option><option>2098</option><option>2099</option><option>2100</option>' . "\n";
			$retval .= '</select>' . "\n";
			$retval .= '<input name="dateChange" type="submit" class="formButtons" id="dateChange" value="Go" onClick="goToSelectedDate()" />' . "\n";
			$retval .= '<input name="epcprev" type="button" id="epcprev" value="&lt;&lt;" class="formButtons" onClick="location=\'index.php?cmd=Calendar&amp;date=' . date('Y-m-d', mktime(0, 0, 0, $month-1, 1, $year)) . '&amp;nbm_id=' . $nbm_id . '&amp;client_id=' . $client_id . '\'" title="Go to ' . date('F Y', mktime(0, 0, 0, $month-1, 1, $year)) . '" />' . "\n";
			$retval .= '<input name="next" type="button" id="next" value="&gt;&gt;" class="formButtons" onClick="location=\'index.php?cmd=Calendar&amp;date=' . date('Y-m-d', mktime(0, 0, 0, $month+1, 1, $year)) . '&amp;nbm_id=' . $nbm_id . '&amp;client_id=' . $client_id . '\'" title="Go to ' . date('F Y', mktime(0, 0, 0, $month+1, 1, $year)) . '" />' . "\n";
		}

		$retval .= '</td>' . "\n";;
		$retval .= "\t\t\t\t" . '</tr>' . "\n";
		$retval .= "\t\t\t" . '</table>' . "\n";
		$retval .= "\t\t" . '</td>' . "\n";
		$retval .= "\t" . '</tr>' . "\n";
	}


		// Summary
//		$retval .= '<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#455678" class="tableListings">' . "\n";
		$retval .= "\t" . '<tr>' . "\n";
		$retval .= "\t\t" . '<td colspan="7" style="background-color: #455678; color: white; padding: 3px">{%summary%}</td>' . "\n";
		$retval .= "\t" . '</tr>' . "\n";
	//	$retval .= '</table>' . "\n";

//	$retval .= "\t" . '<tr>' . "\n";
//	$retval .= "\t\t" . '<td align="center" bgcolor="' . $m_bgcolor . '" colspan="7"><font size="-2"><strong>' . $month_in_word . '</strong></font></td>' . "\n";
//	$retval .= "</tr>\n";


	// Print day names
	$retval .= "\t" . '<tr class="dayNamesTextTOC">' . "\n";
	foreach ($days as $day)
	{
		$retval .= "\t\t" . '<td';
		if (!empty ($colwidth))
		{
			$retval .= ' width="' . $colwidth . '"';
		}
		$retval .= ' class="dayNamesRowTOC">' . $day . '</td>' ."\n";
	}
	$retval .= "\t" . '</tr>' . "\n";


	// do the days now

	if (($fist_dat_start) % 7 != 0)
	{
		$retval .= "\t" . '<tr class="rowsTOC">' . "\n";
	}

	/* Previous Month */

	// Work out previous month
	$prev_month_timestamp =  mktime (0, 0, 1, $month-1, 1, $year);
	$days_in_prev_month = date('t', $prev_month_timestamp);

	// print all before first day start
	for ($i = $fist_dat_start-1; $i >= 0; $i--)
	{
		$day = $days_in_prev_month - $i;
		$retval .= "\t\t" . '<td class="prev_month"><div class="prev_month_day">' . $day . '</div></td>' . "\n";
	}

	// Counts
	$count_meeting                   = 0;
	$count_meeting_awaiting_feedback = 0;
	$count_meeting_attended          = 0;
	$count_meeting_tbr               = 0;
	$count_meeting_cancelled         = 0;
	$count_meeting_in_future         = 0;
	$count_meeting_unknown           = 0;

	/* Current Month */
	// now print days
	for ($i = 1; $i <= $days_in_month; $i++)
	{
		// Set the date currently dealing with
		$date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));

		if (($i + $fist_dat_start) % 7 == 1)
		{
			if ($i != 1)
			{
				$retval .= "\t</tr>\n";
			}
			$retval .= "\t<tr class=\"rowsTOC\">" . "\n";
		}

		// Determine type of day and class (colour) to display
		if ($start_day == 0)
		{
			if (($i + $fist_dat_start) % 7 == 1 || ($i + $fist_dat_start) % 7 == 0)
			{
				$retval .= "\t\t" . '<td class="weekend">';
			}
			else
			{
				$retval .= "\t\t" . '<td class="weekday">';
			}
		}
		elseif ($start_day == 1)
		{
			if (($i + $fist_dat_start) % 7 == 6 || ($i + $fist_dat_start) % 7 == 0)
			{
				$retval .= "\t\t" . '<td class="weekend">';
			}
			elseif (isBankHoliday(date('Y-m-d', mktime(0, 0, 0, $month, $i, $year)), $data))
			{
				$retval .= "\t\t" . '<td class="bank_holiday">';
			}
			else
			{
				$retval .= "\t\t" . '<td class="weekday">';
			}
		}

		$the_date = sprintf("%4d-%02d-%02d", $year, $month, $i);

		// Check if today and/or selected day
		if ($selected == $i && $highlighted_day == $i && $date == $today)
		{
			$css_class = 'today highlighted';
		}
		elseif ($selected == $i && $date == $today)
		{
			$css_class = 'today';
		}
		elseif ($highlighted_day == $i)
		{
			$css_class = 'current_month_day highlighted';
		}
		else
		{
			$css_class = 'current_month_day';
		}


		if ($i == 6)
		{
			$css_class .= ' bank_holiday';
		}



		$day_header_text = $i;
//		if (isBankHoliday(date('Y-m-d', mktime(0, 0, 0, $month, $i, $year)), $data))
//		{
//			$day_header_text .= ' Bank Holiday';
//		}


		if (!is_null($onclick))
		{
			if ($onclick == 'showCalendarDay')
			{
				$date = $year . '-' . substr('00' . $month, -2) . '-' . substr('00' . $i, -2);
				$onclick_link = "javascript:";
				$onclick_link = "doFrameItem('index.php?cmd=CalendarDay&amp;date=$date&amp;nbm_id=$nbm_id&amp;client_id=$client_id');";
			}
			elseif ($onclick == 'showCalendarDate')
			{
				$date = $year . '-' . substr('00' . $month, -2) . '-' . substr('00' . $i, -2);
				$onclick_link = "javascript:showCalendarDate('$date', $nbm_id);";
			}
			$onclick_link .= " return false;";
			$retval .= '<a href="#" onclick="' . $onclick_link . '"><div class="' . $css_class . '">' . $day_header_text . '</div></a>';
		}
		elseif ($day_link)
		{
			$link = $day_link . '&amp;date=' . $year . '-' . substr('00' . $month, -2) . '-' . substr('00' . $i, -2);
			$retval .= '<a href="' . $link . '"><div class="' . $css_class . '">' . $day_header_text . '</div></a>';
		}
		else
		{
			$retval .= '<div class="' . $css_class . '">' . $day_header_text . '</div>';
		}



//		if ($selected == $i)
//		{
//			if ($day_link)
//			{
//				$link = $day_link . '&amp;date=' . $year . '-' . substr('00' . $month, -2) . '-' . substr('00' . $i, -2);
//				$retval .= '<a href="' . $link . '"><div class="todayTOC">' . $i . '</div></a>';
//			}
//			else
//			{
//				$retval .= '<div class="todayTOC">' . $i . '</div>';
//			}
//		}
//		else
//		{
//			if ($day_link)
//			{
//				$link = $day_link . '&amp;date=' . $year . '-' . substr('00' . $month, -2) . '-' . substr('00' . $i, -2);
//				$retval .= '<a href="' . $link . '"><div class="current_month_day">' . $i . '</div></a>';
//			}
//			else
//			{
//				$retval .= '<div class="current_month_day">' . $i . '</div>';
//			}
//		}



		//
		// Handle the data (meetings, actions, events, etc) which should be listed for each day
		//


		$timestamp =  mktime(0, 0, 1, $month, $i, $year);
		$index = date('Y-m-d', $timestamp);

		if (isset($data[$index]))
		{
			if ($group)
			{
				$zz = 1;
				$meetings                = countMeetings($data[$index]);
				$events                  = countEvents($data[$index]);
				$inforeqs                = countInformationRequests($data[$index]);
				$actions                 = countActions($data[$index]);
				$post_initiative_actions = countPostInitiativeActions($data[$index]);

				$retval .= "\n";

				if ($meetings)
				{
					$retval .= "\t\t\t" . '<span class="meeting">&nbsp;&nbsp;</span> ' . $meetings . "\n";
				}
				if ($events)
				{
					$retval .= "\t\t\t" . '<span class="event">&nbsp;&nbsp;</span> ' . $events . "\n";
				}
				if ($inforeqs)
				{
					$retval .= "\t\t\t" . '<span class="info">&nbsp;&nbsp;</span> ' . $inforeqs . "\n";
				}
				if ($post_initiative_actions)
				{
					$retval .= "\t\t\t" . '<span class="post_initiative_action">&nbsp;&nbsp;</span> ' . $post_initiative_actions . "\n";
				}
				if ($actions)
				{
					$retval .= "\t\t\t" . '<span class="action">&nbsp;&nbsp;</span> ' . $actions . "\n";
				}
			}
			else
			{
				foreach ($data[$index] as $d)
				{
					// Continue to next item in data array
					if ($d['type'] == 'bank_holiday')
					{
						continue;
					}

					// Add an id to the div containing a calendar entry
					if ($d['type'] == 'action')
					{
						$_id = ' id="action_' . $d['id'] . '"';
					}
					elseif ($d['type'] == 'event')
					{
						$_id = ' id="event_' . $d['id'] . '"';
					}
					elseif ($d['type'] == 'meeting')
					{
						$_id = ' id="meeting_' . $d['id'] . '"';
					}
					else
					{
						$_id = '';
					}
					$retval .= "\n\t\t" . '<div' . $_id . '>' . "\n";

					$retval .= "\t\t\t" . '<table class="entry">' . "\n";
					$retval .= "\t\t\t\t" . '<tr>' . "\n";

					if (!isset($d['type']) || empty($d['type']))
					{
						$d['type'] = 'sDefault';
					}
					elseif ($d['type'] == 'bank_holiday')
					{
//						continue;
					}
					elseif ($d['type'] == 'meeting')
					{
						$d['type'] = 'meeting';
					}
					elseif ($d['type'] == 'info')
					{
						$d['type'] = 'info';
					}
					elseif ($d['type'] == 'action')
					{
						if ($d['type_id'])
						{
							$d['type'] = 'post_initiative_action';
						}
						else
						{
							$d['type'] = 'action';
						}
					}

					// Output colour block which indicated the entry type
					$retval .= "\t\t\t\t\t" . '<td class="' . $d['type']. '">&nbsp;</td>' . "\n";

					// Open table cell
					$retval .= "\t\t\t\t\t" . '<td width="16">';

					/*
					 * Output icon if type is a meeting
					 */
					if ($d['type'] == 'meeting')
					{

						$count_meeting++;
						if ($d['from'] < date('Y-m-d H:i:s'))
						{
							// Meeting was in the past
							if (in_array($d['status_id'], array(12, 13, 18, 19)))
							{
								// Meeting set
								$retval .= "\t\t\t" . '<img src="' . $image_path . 'help.png" alt="Awaiting Status Update" title="Awaiting Status Update" />' . "\n";
								$count_meeting_awaiting_feedback++;
							}
							elseif (in_array($d['status_id'], array(24, 25, 26, 27, 28, 29, 30, 31, 32)))
							{
								// Meeting attended
								$retval .= "\t\t\t" . '<img src="' . $image_path . 'accept.png" alt="Attended" title="Attended" />' . "\n";
								$count_meeting_attended++;
							}
							elseif (in_array($d['status_id'], array(14, 15, 16, 17)))
							{
								// Meeting to be rearranged
								$retval .= "\t\t\t" . '<img src="' . $image_path . 'refresh.png" alt="To Be Rearranged" title="To Be Rearranged" />' . "\n";
								$count_meeting_tbr++;
							}
							elseif (in_array($d['status_id'], array(20, 21, 22, 23)))
							{
								// Meeting cancelled
								$retval .= "\t\t\t" . '<img src="' . $image_path . 'delete.png" alt="Cancelled" title="Cancelled" />' . "\n";
								$count_meeting_cancelled++;
							}
							else
							{
								// Odd-ball meeting
								$retval .= "\t\t\t" . '<img src="' . $image_path . 'cog.png" alt="Unknown (Status ID ' . $d['status_id'] . ')" title="Unknown (Status ID ' . $d['status_id'] . ')" />' . "\n";
								$count_meeting_unknown++;
							}
						}
						elseif ($d['from'] >= date('Y-m-d H:i:s'))
						{
                            if (in_array($d['status_id'], array(14, 15, 16, 17)))
	                        {
	                            // Meeting to be rearranged
	                            $retval .= "\t\t\t" . '<img src="' . $image_path . 'refresh.png" alt="To Be Rearranged" title="To Be Rearranged" />' . "\n";
	                            $count_meeting_tbr++;
	                        }
	                        elseif (in_array($d['status_id'], array(20, 21, 22, 23)))
	                        {
	                            // Meeting cancelled
	                            $retval .= "\t\t\t" . '<img src="' . $image_path . 'delete.png" alt="Cancelled" title="Cancelled" />' . "\n";
	                            $count_meeting_cancelled++;
	                        }
	                        else
	                        {
	                           // Meeting set in the future
	                            $retval .= "\t\t\t" . '<img src="' . $image_path . 'calendar_view_day.png" alt="Future Meeting" title="Future Meeting" />' . "\n";
	                            $count_meeting_in_future++;
	                        }
						}
					}

					// Close table cell
					$retval .= "</td>\n";

					/*
					 * Rearrangement count
					 */
					$retval .= "\t\t" . '<td width="1">';
					if ($d['rearranged_count'] > 0)
					{
						$retval .= "\t\t\t" . '(' . $d['rearranged_count'] . ')';
					}
					$retval .= "\t\t" . '</td>';

					// Get the 'from' time
					if (preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/', $d['from']))
					{
						$d['from'] = date('G:i', strtotime($d['from']));
					}

					$retval .= "\t\t\t\t\t" . '<td>';
					if (!empty($d['to']))
					{
	//					$retval .= $d['from'] . '-' . $d['to'] . ': ' . $d['title'] . "\n";
					}
					else
					{
						if ($group)
						{
							if ($d['from'] == '00:00')
							{
								$retval .= '<span class="tableTitle">' . $d['subject'] . '</span>';
							}
							else
							{
								$retval .= '<span class="tableTitle">' . $d['from'] . '&nbsp;&nbsp;' . $d['subject'] . '</span>';
							}
						}
						else
						{
							if ($d['from'] == '00:00')
							{
								$retval .= '<span class="tableTitle">' . $d['subject'] . '</span>';
							}
							else
							{
								$retval .= '<span class="tableTitle">' . $d['from'] . '&nbsp;&nbsp;' . $d['subject'] . '</span>';
							}
						}
					}

					$retval .= "</td>\n";

					$retval .= "\t\t\t\t" . '</tr>' . "\n";
					$retval .= "\t\t\t" . '</table>' . "\n";

					// Close div containing calendar entry
					$retval .= "\t\t</div>\n";
				}
			}
		}
		else
		{
//			echo "<br />it's NOT set";
		}

		$retval .= "</td>\n";
	}

	// Output the days for next month which finish of the last row of the calendar grid
	$left_out = ($fist_dat_start + $days_in_month) % 7;
	if ($left_out > 0)
	{
		for ($day_of_month = 1; $day_of_month <= 7-$left_out; $day_of_month++)
		{
			$retval .= '<td class="next_month"><div class="next_month_day">' . $day_of_month . '</div></td>' . "\n";
		}
	}

	// Close the table containing the calendar
	$retval .= "</tr>\n</table>\n";

   // if assign to variable is set
//    if (!empty($assign))
//      {
//       $smarty->assign($assign, $retval);
//       }
//   else {


	$summary = '<div style="float: left; height: 16px">' . $count_meeting . ' meetings in total</div>';


	// Attended
	$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'accept.png" alt="Attended" title="Attended" /></div>';
	$summary .= '<div style="float: left; height: 16px">' . $count_meeting_attended . '</div>';

	// To be Rearranged
	$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'refresh.png" alt="To Be Rearranged" title="To Be Rearranged" /></div>';
	$summary .= '<div style="float: left; height: 16px">' . $count_meeting_tbr . '</div>';

	// Cancelled
	$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'delete.png" alt="Cancelled" title="Cancelled" /></div>';
	$summary .= '<div style="float: left; height: 16px">' . $count_meeting_cancelled . '</div>';

	// Unknown
	$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'help.png" alt="Awaiting Status Update" title="Awaiting Status Update" /></div>';
	$summary .= '<div style="float: left; height: 16px">' . $count_meeting_awaiting_feedback . '</div>';

	// Future Meeting
	$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'calendar_view_day.png" alt="Future Meeting" title="Future Meeting" /></div>';
	$summary .= '<div style="float: left; height: 16px">' . $count_meeting_in_future . '</div>';

	// Odd-ball Meeting
	if ($count_meeting_unknown > 0)
	{
		$summary .= '<div style="float: left; margin-left: 10px; margin-right: 3px; height: 16px"><img src="' . $image_path . 'cog.png" alt="Unknown" title="Unknown" /></div>';
		$summary .= '<div style="height: 16px">' . $count_meeting_unknown . '</div>';
	}

	$retval = str_replace('{%summary%}', $summary, $retval);


	//
	// Output legend
	//
	if ($legend)
	{
		$retval .= '<div id="legend">' . "\n";
		$retval .= '<span class="post_initiative_action">&nbsp;&nbsp;</span> Post Initiative Action' . "\n";
		$retval .= '<span class="action">&nbsp;&nbsp;</span> Action' . "\n";
		$retval .= '<span class="event">&nbsp;&nbsp;</span> Event' . "\n";
		$retval .= '<span class="meeting">&nbsp;&nbsp;</span> Meeting' . "\n";
		$retval .= "</div>\n";
	}

	$retval .= "<!-- /smarty_function_calendar_month -->\n\n";

	return $retval;
}

/**
 * Remove completed items from the data array.
 * @param array $data
 * @return array
 */
function removeCompletedItems($data)
{
	$new_array = array();
	foreach ($data as $key => $value)
	{
		$_array = array();
		foreach ($value as $item)
		{
			if ($item['completed'] == 0)
			{
				$_array[] = $item;
			}
		}
		$new_array[$key] = $_array;
	}
	return $new_array;
}

/**
 * Count the number of meetings.
 * @param array $data
 * @return integer
 */
function countMeetings($data)
{
	$count = 0;
	foreach ($data as $d)
	{
		if ($d['type'] == 'meeting')
		{
			$count++;
		}
	}
	return $count;
}

/**
 * Count the number of events.
 * @param array $data
 * @return integer
 */
function countEvents($data)
{
	$count = 0;
	foreach ($data as $d)
	{
		if ($d['type'] == 'event')
		{
			$count++;
		}
	}
	return $count;
}

/**
 * Count the number of information requests.
 * @param array $data
 * @return integer
 */
function countInformationRequests($data)
{
	$count = 0;
	foreach ($data as $d)
	{
		if ($d['type'] == 'info')
		{
			$count++;
		}
	}
	return $count;
}

/**
 * Count the number of actions.
 * @param array $data
 * @return integer
 */
function countActions($data)
{
	$count = 0;
	foreach ($data as $d)
	{
		if ($d['type'] == 'action' && (!isset($d['type_id']) || empty($d['type_id'])))
		{
			$count++;
		}
	}
	return $count;
}

/**
 * Count the number of post initiative actions.
 * @param array $data
 * @return integer
 */
function countPostInitiativeActions($data)
{
	$count = 0;
	foreach ($data as $d)
	{
		if ($d['type'] == 'action' && $d['type_id'])
		{
			$count++;
		}
	}
	return $count;
}

/**
 * Determine whether a given day is a bank holiday.
 * @param string $date in format YYYY-MM-DD
 * @param array $data array containing calendar data
 * @return boolean
 */
function isBankHoliday($date, $data)
{
	$day_data = $data[$date];
	foreach ($day_data as $dd)
	{
		if ($dd['type'] == 'bank_holiday')
		{
			return true;
		}
	}
	return false;
}

///**
// * Return the name of a bank holiday if it is one.
// * @param string $date in format YYYY-MM-DD
// * @param array $data array containing calendar data
// * return string
// */
//function getBankHolidayName($date, $data)
//{
//	$day_data = $data[$date];
//	foreach ($day_data as $dd)
//	{
//		if ($dd['type'] == 'bank_holiday')
//		{
//			return $dd['subject'];
//		}
//	}
//	return '';
//}

?>