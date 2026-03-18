<?php

/**
 * Defines the app_domain_CalendarReader class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReaderObject.php');
require_once('app/mapper/CalendarReaderMapper.php');

/**
 * @package Alchemis
 */
class app_domain_CalendarReader extends app_domain_ReaderObject
{
	/**
	 * By declaring private, we prevent instatiation by other objects.
	 */
	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * Returns the calendar data for a given day, filtered to an NBM or client if suppied.
	 * @param string $date the day in the format 'YYYY-MM-DD'
	 * @param integer $nbm_id
	 * @param integer $client_id
	 * @return array associative array of calendar data
	 */
	public static function getDay($date, $nbm_id = null, $client_id = null)
	{
//		echo "<p><b>app_domain_CalendarReader::getDay($date, $nbm_id, $client_id)</b></p>";
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))
		{
			$entries[$date] = array();

			// TODO
			//  - add result from mapper
			$reader = self::getReader(__CLASS__);
			if ($rows = $reader->findByDate($date, $nbm_id, $client_id))
			{
// 				echo '<hr /><pre>';
// 				print_r($rows);
// 				echo '</pre><hr />';
                if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row)
					{
						$entries[$date][] = array(	'id'                 => $row['id'],
													'from'               => $row['date'],
	//												'to'                 => $row['date'],
													'subject'            => $row['subject'],
													'notes'              => $row['notes'],
													'type'               => $row['type'],
													'type_id'            => $row['type_id'],
													'company_id'         => $row['company_id'],
													'post_id'            => $row['post_id'],
													'reminder_date'      => $row['reminder_date'],
													'post_initiative_id' => $row['post_initiative_id'],
													'initiative_id' 	 => $row['initiative_id'],
													'completed'          => $row['completed'],
													'status_id'          => $row['status_id'],
													'rearranged_count'   => $row['rearranged_count']);
					}
                }
			}
			return $entries;
		}
		else
		{
			throw new Exception('Date parameter is not in the format YYYY-MM-DD');
		}
	}

	/**
	 * Returns the calendar data for a given period.
	 * @param string $from the first day in the format 'YYYY-MM-DD'
	 * @param string $to the last day in the format 'YYYY-MM-DD'
	 * @return array associative array of calendar data
	 */
	public static function getDateRange($from, $to, $nbm_id = null, $client_id = null)
	{
		// Check date formats
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to))
		{
			if ($to < $from)
			{
				$tmp = $from;
				$from = $to;
				$to = $tmp;
			}

			// Pre-populate all dates in range with empty arrays so the calendar renders
			// even days with no events — then fill in results from a single batch query.
			$range = array();
			$cursor = $from;
			while ($cursor <= $to)
			{
				$range[$cursor] = array();
				$cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
			}

			// Single query for the whole range instead of one query per day
			$reader = self::getReader(__CLASS__);
			$rows   = $reader->findByDateRange($from, $to, $nbm_id, $client_id);

			if ($rows)
			{
				foreach ($rows as $row)
				{
					$date = substr($row['date'], 0, 10);
					if (isset($range[$date]))
					{
						$range[$date][] = array(
							'id'                 => $row['id'],
							'from'               => $row['date'],
							'subject'            => $row['subject'],
							'notes'              => $row['notes'],
							'type'               => $row['type'],
							'type_id'            => $row['type_id'],
							'company_id'         => $row['company_id'],
							'post_id'            => $row['post_id'],
							'reminder_date'      => $row['reminder_date'],
							'post_initiative_id' => $row['post_initiative_id'],
							'initiative_id'      => $row['initiative_id'],
							'completed'          => $row['completed'],
							'status_id'          => $row['status_id'],
							'rearranged_count'   => $row['rearranged_count'],
						);
					}
				}
			}

			return $range;
		}
		else
		{
			throw new Exception('One, or both, of the supplied parameters is not in the format YYYY-MM-DD');
		}
	}

	/**
	 * Returns the calendar data for a given week period.
	 * @param string $date the first day of the week in the format 'YYYY-MM-DD'
	 * @return array associative array of calendar data
	 */
	public static function getWeek($date)
	{
		// If $time is not in format yyyy-mm-dd
		if (preg_match('/^\d{4}-\d{2}-\d{2}/', $date, $found))
		{
			// string format YYYY-MM-DD
			$from = $date;
			$to   = date('Y-m-d', strtotime($date . ' +6 days'));
			return self::getDateRange($from, $to);
		}
		else
		{
			throw new Exception('Supplied parameter is not in the format YYYY-MM-DD');
		}
	}

	/**
	 * Returns the calendar data for a given calendar month.
	 * @param string $year_month the month in the format 'YYYY-MM'
	 * @param integer $client_id filtered to a given client
	 * @return array associative array of calendar data
	 */
	public static function getMonth($year_month, $nbm_id = null, $client_id = null)
	{
		// If $time is not in format yyyy-mm-dd
		if (preg_match('/^\d{4}-\d{2}/', $year_month, $found))
		{
			// string format YYYY-MM
			$from = $year_month . '-01';
			$to   = date('Y-m-d', strtotime($from . ' +1 month -1 day'));
			return self::getDateRange($from, $to, $nbm_id, $client_id);
		}
		else
		{
			throw new Exception('Supplied parameter is not of the form YYYY-MM');
		}
	}

	/**
	 * Returns the calendar data for a given calendar year.
	 * @param string $year the year in the format 'YYYY'
	 * @param integer $client_id filtered to a given client
	 * @return array associative array of calendar data
	 */
	public static function getYear($year, $nbm_id = null, $client_id = null)
	{
		if (preg_match('/^\d{4}/', $year, $found))
		{
			// string format YYYY-MM
			$year  = substr($year, 0, 4);

//			$days_in_month = date('t', mktime (0, 0, 1, $month, 1, $year));
//			$from = date('Y-m-d', mktime(0, 0, 1, $month, 1, $year));
//			$to = date('Y-m-d', mktime(0, 0, 1, $month, $days_in_month, $year));

			$from = $year . '-01-01';
			$to   = $year . '-12-31';

			return self::getDateRange($from, $to, $nbm_id, $client_id);
		}
		else
		{
			throw new Exception('Supplied parameter is not of the form YYYY-MM');
		}
	}

	
	/**
	* Returns the meeting data for a given client id.
	* @param integer $client_id filtered to a given client
	* @return array associative array of calendar data
	*/
	public static function findMeetingsByClient($client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->findMeetingsByClient($client_id);
	}
	
	
}

?>