<?php

set_include_path(	 
					'.' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 
					':' . get_include_path());

// APP_DIRECTORY is required otherwise an error is thrown in app_base_Registry
define('APP_DIRECTORY', './');

// Ensure the maximum execution time is at least 300 seconds
if (ini_get('max_execution_time') < 500)
{
	set_time_limit(500);
}

// change directory so we can pick up the app_base_registry classes and files
chdir('../../');

require_once('ClientCalendarPublisher.class.php');

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$calendar = new batch_calendarPublisher_ClientCalendarPublisher();
	echo $calendar->makeDiary($_REQUEST['id']);
} else {
	echo 'Bad data request';
}

?>