<?php
set_include_path(	'.' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include' .
					':.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 
					':' . get_include_path());

// APP_DIRECTORY is required otherwise an error is thrown in app_base_Registry
define('APP_DIRECTORY', './');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

require_once('Import.class.php');
require_once('include/Zend/Debug.php');


// change directory so we can pick up the app_base_registry classes and files
chdir('../../');

new batch_import_Import();
?>