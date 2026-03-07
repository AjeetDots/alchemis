<?php

set_include_path(	'.' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 
					':' . get_include_path());

// APP_DIRECTORY is required otherwise an error is thrown in app_base_Registry
define('APP_DIRECTORY', './');

// change directory so we can pick up the app_base_registry classes and files
chdir('../../');

ini_set('max_execution_time', 0);
require_once('EmailTrapper.class.php');

new batch_emailTrapper_EmailTrapper();

echo '<p>Finished processing EmailTrapper</p>';

?>