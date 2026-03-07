<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

if (!defined('SMARTY_PLUGINS_DIR'))
{
	define('SMARTY_PLUGINS_DIR', SMARTY_DIR . 'plugins' . DIRECTORY_SEPARATOR);
}
require_once(SMARTY_PLUGINS_DIR . 'function.popup.php');

/**
 * Smarty {html_radios} function plugin
 *
 * File:       function.calendar_day.php<br />
 * Type:       function<br />
 * Name:       calendar_day<br />
 * Date:       0May/2007<br />
 * Purpose:    Prints out a calendar day listing for one or more days<br />
 * Input:<br />
 *           - options        (required) - associative array
 *           - inc_empty_days (optional) - boolean
 *           - legend     (optional) - boolean whether to show the colour-legend
 *           - assign         (optional) - assign the output as an array to this variable
 * Examples:
 * <pre>
 * {calendar_day data=$day_data}
 * {calendar_day data=$day_data assign=listing}
 * </pre>
 * @author     Ian Munday <ian.munday@illumen.co.uk>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_calendar_day($params, &$smarty)
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

	// Legend
	if (empty($legend))
	{
		$legend = false;
	}

	// Path to images
	$image_path = 'app/view/images/icons/';

	$retval = "<!-- smarty_function_calendar_day -->\n";

//	$retval .= '<div style="border: 1px solid red; margin">' . "\n";
//	$retval .= '{%count%}';
//	$retval .= "</div>\n";


//	echo $retval;




//	$retval = str_replace('{%count%}', $summary, $retval);
//	echo $retval;


	foreach ($data as $date => $entries)
	{
//		echo "<p>\$date = $date ; \$entries = $entries";
		if (preg_match('/^\d{4}-\d{2}-\d{2}/', $date))
		{
			// string format YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
			$year  = substr($date, 0, 4);
			$month = substr($date, 5, 2);
			$day   = substr($date, 8, 2);
			if (checkdate($month, $day, $year))
			{
				$time = date('l jS F Y', mktime(0, 0, 1, $month, $day, $year));
			}
		}
		else
		{
			$smarty->trigger_error('calendar_day: date format not of format YYYY-MM-DD', E_USER_NOTICE);
		}

		// Date heading
		$retval .= '<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#455678" class="tableListings">' . "\n";
		$retval .= "\t" . '<tr class="tableDate">' . "\n";
		$retval .= "\t\t" . '<td><span class="newDate">' . $time . '</span></td>' . "\n";
		$retval .= "\t" . '</tr>' . "\n";
		$retval .= '</table>' . "\n";

		// Summary
		$retval .= '<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#455678" class="tableListings">' . "\n";
		$retval .= "\t" . '<tr>' . "\n";
		$retval .= "\t\t" . '<td style="color: white">{%summary%}</td>' . "\n";
		$retval .= "\t" . '</tr>' . "\n";
		$retval .= '</table>' . "\n";

		// Calendar
		$retval .= '<table id="calendar_day" class="tableListings">' . "\n";

		// Counts
		$count_meeting                   = 0;
		$count_meeting_awaiting_feedback = 0;
		$count_meeting_attended          = 0;
		$count_meeting_tbr               = 0;
		$count_meeting_cancelled         = 0;
		$count_meeting_in_future         = 0;
		$count_meeting_unknown           = 0;

		if ($entries)
		{
			foreach ($entries as $entry)
			{
				// Strip whitespace from the beginning and end of notes
				$entry['notes'] = trim($entry['notes']);

				// Determine which CSS class to use based upon the calender entry type (sets colour)
				if ($entry['type'] == 'action')
				{
					if ($entry['type_id'])
					{
						$css_class = 'post_initiative_action';
					}
					else
					{
						$css_class = $entry['type'];
					}
				}
				elseif ($entry['type'])
				{
					$css_class = $entry['type'];
				}
				else
				{
					$css_class = 'sDefault';
				}

				// Determine whether to use the completed CSS class
				if ($entry['completed'])
				{
					$completed_css = ' completed';
				}
				else
				{
					$completed_css = '';
				}

				// Start output of a table row
				if ($entry['type'] == 'action')
				{
					$retval .= "\t" . '<tr id="action_' . $entry['id'] . '">' . "\n";
				}
				elseif ($entry['type'] == 'event')
				{
					$retval .= "\t" . '<tr id="event_' . $entry['id'] . '">' . "\n";
				}
				elseif ($entry['type'] == 'meeting')
				{
					$retval .= "\t" . '<tr id="meeting_' . $entry['id'] . '">' . "\n";
				}
				else
				{
					$retval .= "\t" . '<tr>' . "\n";
				}
				$retval .= "\t\t" . '<td width="5" align="left" valign="top" class="tableCategory ' . $css_class . '">&nbsp;</td>' . "\n";



				// Open table cell
				if ($entry['rearranged_count'] == 0)
				{
					$retval .= "\t\t" . '<td width="16" colspan="2" class="meeting_status">';
				}
				else
				{
					$retval .= "\t\t" . '<td width="16" class="meeting_status">';
				}

				// Output icon if type is a meeting
				if ($entry['type'] == 'meeting')
				{
					$count_meeting++;

					if ($entry['from'] < date('Y-m-d H:i:s'))
					{
						// Meeting was in the past
						if (in_array($entry['status_id'], array(12, 13, 18, 19)))
						{
							// Meeting set
							$retval .= "\t\t\t" . '<img src="' . $image_path . 'help.png" alt="Awaiting Status Update" title="Awaiting Status Update" />' . "\n";
							$count_meeting_awaiting_feedback++;
						}
						elseif (in_array($entry['status_id'], array(24, 25, 26, 27, 28, 29, 30, 31, 32)))
						{
							// Meeting attended
							$retval .= "\t\t\t" . '<img src="' . $image_path . 'accept.png" alt="Attended" title="Attended" />' . "\n";
							$count_meeting_attended++;
						}
						elseif (in_array($entry['status_id'], array(14, 15, 16, 17)))
						{
							// Meeting to be rearranged
							$retval .= "\t\t\t" . '<img src="' . $image_path . 'refresh.png" alt="To Be Rearranged" title="To Be Rearranged" />' . "\n";
							$count_meeting_tbr++;
						}
						elseif (in_array($entry['status_id'], array(20, 21, 22, 23)))
						{
							// Meeting cancelled
							$retval .= "\t\t\t" . '<img src="' . $image_path . 'delete.png" alt="Cancelled" title="Cancelled" />' . "\n";
							$count_meeting_cancelled++;
						}
						else
						{
							// Odd-ball meeting
							$retval .= "\t\t\t" . '<img src="' . $image_path . 'cog.png" alt="Unknown (Status ID ' . $entry['status_id'] . ')" title="Unknown (Status ID ' . $entry['status_id'] . ')" />' . "\n";
							$count_meeting_unknown++;
						}
					}
					elseif ($entry['from'] >= date('Y-m-d H:i:s'))
					{
					    if (in_array($entry['status_id'], array(14, 15, 16, 17)))
                        {
                            // Meeting to be rearranged
                            $retval .= "\t\t\t" . '<img src="' . $image_path . 'refresh.png" alt="To Be Rearranged" title="To Be Rearranged" />' . "\n";
                            $count_meeting_tbr++;
                        }
                        elseif (in_array($entry['status_id'], array(20, 21, 22, 23)))
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
				else
				{
//					$retval .= "\t\t\t" . '&nbsp;</td>' . "\n";
				}

				// Close table cell
				$retval .= "\t\t" . '</td>';


				/*
				 * Rearrangement count
				 */
				if ($entry['rearranged_count'] > 0)
				{
					$retval .= "\t\t" . '<td width="1" class="meeting_status">' . "\n";
					$retval .= "\t\t\t" . '(' . $entry['rearranged_count'] . ')';
					$retval .= "\t\t</td>\n";
				}



//				$retval .= "\t\t" . '&nbsp;</td>' . "\n";


//				$retval .= "\t\t" . '<td align="left" valign="top" bgcolor="#FFFDF2" class="tableTitle">' . "\n";


				// Get the 'from' time
				if (preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/', $entry['from']))
				{
					$entry['from'] = date('G:i', strtotime($entry['from']));
				}

				// Get the 'to' time
				if (preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/', $entry['to']))
				{
					$entry['to'] = date('G:i', strtotime($entry['to']));
				}



				if ($entry['type'] == 'bank_holiday')
				{
					$retval .= "\t\t" . '<td class="tableTitle">' . "\n";
				}
				elseif (!empty($entry['to']))
				{
					$retval .= "\t\t" . '<td class="tableTitle' . $completed_css . '">';
					$retval .= "\t\t\t" . $entry['from'] . ' - ' . $entry['to'];
					$spacer = '&nbsp;';
				}
				elseif ($entry['from'] != '00:00' && $entry['from'] != '0:00')
				{
					$retval .= "\t\t" . '<td class="tableTitle' . $completed_css . '">';
					$retval .= "\t\t" . $entry['from'];
					$spacer = '&nbsp;';
				}
				else
				{
					$retval .= "\t\t" . '<td class="tableTitle">';
					$spacer = '';
				}

				// Output title / subject
				if (!empty($entry['subject']))
				{
					// Strike out completed items
					if ($entry['completed'])
					{
						$retval .= "\n\t\t\t" . $spacer . '<span class="completed">' . $entry['subject'] . '</span>';
					}
					else
					{
						$retval .= "\n\t\t\t" . $spacer . $entry['subject'];
					}
				}
				$retval .= "\n";

				// Output description / notes
				if (!empty($entry['notes']))
				{
					// Strike out completed items
					$retval .= "\t\t\t" . '<br /><span class="notes' . $completed_css . '">' . $entry['notes'] . '</span>' . "\n";
				}

				if (!empty($entry['reminder_date']))
				{
					$retval .= "\t\t\t" . '<br /><span class="reminder">Reminder: ' . Utils::DateFormat($entry['reminder_date'], 'YYYY-MM-DD HH:MM:SS', 'DD/MM/YYYY HH:MM:SS') . '</span>' . "\n";
				}

				$retval .= "\t\t" . '</td>' . "\n";

				// Add edit links
				if ($entry['type'] != 'bank_holiday')
				{
					$edit_onclick   = null;
					$delete_onclick = null;
					$view_onclick   = null;
					if ($entry['type'] == 'action')
					{
						if ($entry['type_id'])
						{
							$view_onclick = "javascript:showPost(" . $entry['company_id'] . ", " . $entry['post_id'] . ", " . $entry['initiative_id'] . "); return false;";
						}
						else
						{
							$edit_onclick   = "javascript:doFrameItem('index.php?cmd=ActionCreate&amp;referrer=Calendar&amp;action_id=" . $entry['id'] . "'); return false;";
							$delete_onclick = "javascript:doFrameItem('index.php?cmd=ActionDelete&amp;referrer=Calendar&amp;referrer_date=" . $date . "&amp;action_id=" . $entry['id'] . "'); return false;";
						}
					}
					elseif ($entry['type'] == 'event')
					{
						$edit_onclick   = "javascript:doFrameItem('index.php?cmd=EventCreate&amp;referrer=Calendar&amp;event_id=" . $entry['id'] . "'); return false;";
						$delete_onclick = "javascript:doFrameItem('index.php?cmd=EventDelete&amp;referrer=Calendar&amp;referrer_date=" . $date . "&amp;event_id=" . $entry['id'] . "'); return false;";
					}
					elseif ($entry['type'] == 'meeting')
					{
//						$meeting_count++;
						$view_onclick   = "javascript:showPost(" . $entry['company_id'] . ", " . $entry['post_id'] . ", " . $entry['initiative_id'] . "); return false;";

//						/* If prior to today then need to output an icon
//						 *  - attended:   tick
//						 *  - cancelled:  cross
//						 *  - unknown:    question mark
//						 */
//						if ($entry['from'] < date('Y-m-d H:i:s'))
//						{
//							if ($entry['status_id'] == 1)
//							{
//								$count_meeting_attended++;
//							}
//							elseif ($entry['status_id'] == 2)
//							{
//								$count_meeting_cancelled++;
//							}
//							else
//							{
//								$count_meeting_unknown++;
//							}
//						}
					}

					if ($edit_onclick && $delete_onclick)
					{
						$retval .= "\t\t" . '<td class="entry_edit">';
						$retval .= '<a href="#" onclick="' . $edit_onclick . '">Edit</a><br />';
						$retval .= '<a href="#" onclick="' . $delete_onclick . '">Delete</a>';
						$retval .= '</td>' . "\n";
					}
					elseif ($edit_onclick)
					{
						$retval .= "\t\t" . '<td class="entry_edit"><a href="#" onclick="' . $edit_onclick . '">Edit</a></td>' . "\n";
					}
					elseif ($delete_onclick)
					{
						$retval .= "\t\t" . '<td class="entry_edit"><a href="#" onclick="' . $delete_onclick . '">Delete</a></td>' . "\n";
					}
					elseif ($view_onclick)
					{
						$retval .= "\t\t" . '<td class="entry_edit"><a href="#" onclick="' . $view_onclick . '">View</a></td>' . "\n";
					}
					else
					{
						$retval .= "\t\t" . '<td class="entry_edit"></td>' . "\n";
					}
				}

				// Close row
				$retval .= "\t" . '</tr>' . "\n";
			}
		}
		else
		{
			$retval .= '<tr>' . "\n";
			$retval .= '<td class="tableTitle" style="text-align: center; border: 1px solid #FFFDF2"><span class="notes">No entries</span></td>' . "\n";
			$retval .= '</tr>' . "\n";
		}
		$retval .= '</table>' . "\n";
	}




//	$src = '<img src="' . $image_path . '" alt="Attended" title="Attended" />';

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
	$summary .= '<div style="height: 16px">' . $count_meeting_in_future . '</div>';

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
		$retval .= '<span class="post_initiative_action">&nbsp;&nbsp;</span> Post Initiative Action';
		$retval .= '<span class="action">&nbsp;&nbsp;</span> Action';
		$retval .= '<span class="event">&nbsp;&nbsp;</span> Event';
		$retval .= '<br /><br />';
		$retval .= '<span class="meeting">&nbsp;&nbsp;</span> Meeting';
		$retval .= "</div>\n";
	}

	$retval .= "<!-- /smarty_function_calendar_day -->\n\n";

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

///**
// * Determine whether a given day is a bank holiday.
// * @param string $date in format YYYY-MM-DD
// * @param array $data array containing calendar data
// * @return boolean
// */
//function isBankHoliday($date, $data)
//{
//	$day_data = $data[$date];
//	foreach ($day_data as $dd)
//	{
//		if ($dd['type'] == 'bank_holiday')
//		{
//			return true;
//		}
//	}
//	return false;
//}
//
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