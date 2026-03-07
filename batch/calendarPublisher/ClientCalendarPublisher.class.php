<?php

require_once('batch/BatchProcess.class.php');

class batch_calendarPublisher_ClientCalendarPublisher extends batch_BatchProcess
{
	function init() {
		
	}
	
	function makeDiary($clientId) {
		$select = $this->db->select();
		$select->from(array('cli' => 'tbl_clients'), array('id', 'name'));
		$select->where('publish_diary = 1');
		$select->where('cli.id = ?', $clientId);
		
		if ($client) {
			$client = $this->db->fetchRow($select);
			$calendar_data = $this->findMeetingsByClient($client['id']);
			$file_data = $this->makeIcsTemplate($client['id'], $calendar_data);
			return $file_data;
		} else {
			exit(0);
		}

	
		/*
		 // following section to be used if requirement to write data to static ics file
		$filename = 'client_calendar_' . $client['id'] . '.ics';
		$filePath = APP_DIRECTORY . 'var/';
		$filename = $filePath . $filename;
		$handle = fopen($filename, 'w') or die('Cannot open file:  ' . $filename); //implicitly creates file
		fwrite($handle, $file_data);
		fclose($handle);
		*/
		
		
	}
	
	function findMeetingsByClient($client_id) {
		// Create temporary table of number of times meetings have been rearranged
		$sql = 'CREATE TEMPORARY TABLE findByDate_t2 ' .
							'SELECT id ' .
							'FROM tbl_meetings_shadow ' .
							'WHERE status_id IN (18, 19) ' .
							"AND shadow_type = 'u' " .
							'GROUP BY id, date;';
		$this->db->getConnection()->query($sql);
	
	
		$sql = 'CREATE TEMPORARY TABLE findByDate_t1 ' .
			                'SELECT id, count(id) AS rearranged_count ' .
			                'FROM findByDate_t2 ' .
			                'GROUP BY id;';
		$this->db->getConnection()->query($sql);
	
		$select = $this->db->select();
		$select->from(array('m' => 'vw_calendar_meetings'), array(
				'id' 				=> 'id', 
				'date' 				=> 'date', 
				'end_date' 			=> 'ADDTIME(date, \'01:00:00\')', 
				'reminder_date' 	=> 'reminder_date', 
				'subject' 			=> 'CONCAT(client, \' / \', company)',
				'notes'				=> 'notes',
				'type'				=> 'CONCAT(\'' .  new Zend_Db_Expr('meeting') . '\')', // note: need to use the CONCAT here to force the Zend_Db_Select to add quotations around the word meeting
				'status_id'			=> 'status_id',
				'rearranged_count'	=> 'IFNULL(t1.rearranged_count, 0)',
				'created_at'		=> 'created_at',
				'client_id'			=> 'client_id',
				'random_number'		=> 'RIGHT(RAND(), 10)',
			)
		);
		
		$select->joinLeft(array('t1' => 'findByDate_t1'), 'm.id = t1.id', array());
		$select->where('m.client_id = ?', $client_id);
		$select->where('m.date >= ?', '2011-01-01 00:00:00');
		$select->order('m.date DESC');
		$select->limit(15);
		
// 		$sql = 'SELECT m.id, m.date, ADDTIME(m.date, \'01:00:00\') AS end_date, m.reminder_date, CONCAT(m.client, \' / \', m.company) AS subject, m.notes, \'meeting\' AS type, m.status_id AS status_id, ' .
// 					'IFNULL(t1.rearranged_count, 0) AS rearranged_count, m.created_at, m.client_id, RIGHT(RAND(), 10) as random_number ' .
// 					'FROM vw_calendar_meetings AS m ' .
// 					'LEFT JOIN findByDate_t1 AS t1 ON m.id = t1.id ' .
// 					'WHERE m.client_id = ' . $client_id . ' ' .
// 					'AND m.date >= \'2011-01-01 00:00:00\' ' .
// 					'ORDER BY m.date DESC LIMIT 15;';
		$result = $this->db->fetchAll($select);
	
		$this->db->getConnection()->query('DROP TEMPORARY TABLE findByDate_t2');
		$this->db->getConnection()->query('DROP TEMPORARY TABLE findByDate_t1');
		return $result;
	
	}
	
	function makeIcsTemplate($client_id, $calendar_data) {
		$template =
			"BEGIN:VCALENDAR\n" .
			"VERSION:2.0\n" .
			"METHOD:PUBLISH\n" .
			"X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n" .
			"BEGIN:VTIMEZONE\n" .
			"TZID:GMT Standard Time\n" .
			"BEGIN:STANDARD\n" .
			"DTSTART:16011028T020000\n" .
			"RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10\n" .
			"TZOFFSETFROM:+0100\n" .
			"TZOFFSETTO:-0000\n" .
			"END:STANDARD\n" .
			"BEGIN:DAYLIGHT\n" .
			"DTSTART:16010325T010000\n" .
			"RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3\n" .
			"TZOFFSETFROM:-0000\n" .
			"TZOFFSETTO:+0100\n" .
			"END:DAYLIGHT\n" .
			"END:VTIMEZONE\n";
	
		foreach ($calendar_data as $calendar_item) {
			$template .=
				'BEGIN:VEVENT' . "\n" .
				'CREATED:' 	. date('Ymd\THis\Z', strtotime($calendar_item['created_at'])) . "\n" . 
				'UID:'		. date('Ymd', strtotime($calendar_item['created_at'])) . $calendar_item['random_number'] . "\n" . 
				'STATUS:CONFIRMED' . "\n" .
				'TRANSP:OPAQUE' . "\n" .
				'SUMMARY:'	. $calendar_item['subject'] . "\n" .
				'DESCRIPTION:' . $calendar_item['subject'] . "\n" .
				'URL;VALUE=URI:http://localhost:8888/alchemis-trunk/index.php?cmd=Home&redirect=Calendar/date/' . date('Y-m-d', strtotime($calendar_item['date'])) . '/client_id/' . $client_id . "\n" .
				'DTSTART:'	. date('Ymd\THis\Z', strtotime($calendar_item['date'])) . "\n" .
				'DTEND:'	. date('Ymd\THis\Z', strtotime($calendar_item['end_date'])) . "\n" .
				'DTSTAMP:'	. date('Ymd\THis\Z', strtotime($calendar_item['created_at'])) . "\n" .
				'SEQUENCE:0 ' . "\n" .
				'END:VEVENT' . "\n";
		}
	
		$template .= 'END:VCALENDAR';
	
		return $template;
	}
}

?>