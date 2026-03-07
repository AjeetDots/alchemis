<html="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
</head>


<body>

<h1>UTF-8 encoded page</h1>

<?php

// Record the time taken to run this script
$timeStart    = gettimeofday();
$timeStart_uS = $timeStart['usec'];
$timeStart_S  = $timeStart['sec'];

// Ensure the maximum execution time is at least 300 seconds
//if (ini_get('max_execution_time') < 300)
//{
	set_time_limit(0);
//}

ini_set('memory_limit', '256M'); 

require_once('/var/www/html/include/EasySql/EasySql.class.php');

//require_once('/Users/david/Sites/alchemis/include/EasySql/EasySql.class.php');
//$utf8_path = '/Users/david/Sites/alchemis/include/utf8/';

//require_once $utf8_path . '/utf8.php';
//    require_once $utf8_path . '/utils/validation.php';
//    require_once $utf8_path . '/utils/ascii.php';
//    require_once $utf8_path . '/utils/unicode.php';    

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;

// Enter dates for query here
//$sql = "select pin.note, pin.id from tbl_communications com " . 
//		"join tbl_post_initiatives pi on com.post_initiative_id = pi.id " .
//		"join tbl_post_initiative_notes pin on pin.id = com.note_id " .
//		"where communication_date >= '2009-01-01 00:00:00' and " .
//		"communication_date < '2009-12-31 00:00:00' and " . 
//		"pi.initiative_id > 1 " .
//		"order by com.id";

//$sql = "select id, address_2 from tbl_sites where id = 21425";

// Don't touch this line
//$sql = "select id, note from tbl_post_initiative_notes where id = 1344027";
//$sql = "select id, note from tbl_post_initiative_notes  order by id desc limit 10000";
$sql = "select id,note from tbl_post_initiative_notes where for_client = 1 and id >= 1 and id < 250000 order by id";
// Show sql query on screen
echo "<p>$sql</p>";

// Get results
$results = $db->get_results($sql, ARRAY_A);

// Print number of rows from query
echo $db->num_rows . '<br /><br />';

// Decide which bits of the process we want to use
// Show character hex array
$show_character_hex_array = false;
// Detect bad characters
$detect_bad_characters = false;
// Show bad characters
$show_bad_characters = false;
// Detect bad sequences
$detect_bad_sequences = true; 
// Correct bad sequences
$correct_bad_sequences = true; 

foreach ($results as $row)
{
	$str = trim($row['note']);

//-------------------------
if ($show_character_hex_array)
{
	$arr = str_split($str);
	foreach ($arr as $item)
	{
		echo $item . ' : ' . bin2hex($item) . '<br />';
	}
}
//--------------------------

	if	($detect_bad_characters)
	{
		if (checkForBadCharacter($str))
		{
			
			$bad_records[] = $row['id'];
			if ($show_bad_characters)
			{
				echo $row['id'] . ' : ' . $row['note'] . '<br /><br />';
			}
		}
		
	}
	
	if ($detect_bad_sequences)
	{
		if (checkForBadSequence($str))
		{
			$bad_seq[] = $row['id'];

			if ($correct_bad_sequences)
			{
				// make correction
				$str_new = correctBadSequences($str);
				
				$sql = 'update tbl_post_initiative_notes set note=\'' . addslashes($str_new) . '\' where id = ' . $row['id'];
				echo $sql . '<br /><br />';
				$db->query($sql);
				//echo 'Rows affected: '. $db->rows_affected . '<br /><br />';
				echo '---------------------------<p></p>';
			}
		}
		
	}
	
}	

//exit();

echo '<pre>';
print_r($bad_records);
echo '</pre>';

echo "Bad sequence";
echo '<pre>';
print_r($bad_seq);
echo '</pre>';

//
// Finish up
//
echo "<p>Done.</p>";
$timeEnd    = gettimeofday(); 
$timeEnd_uS = $timeEnd["usec"]; 
$timeEnd_S  = $timeEnd["sec"]; 
$ExecTime_S = ($timeEnd_S + ($timeEnd_uS / 1000000)) - ($timeStart_S + ($timeStart_uS / 1000000)); 
echo '<div style="text-align: center; padding-bottom: 5px">Execution Time: ' . round($ExecTime_S, 3) . ' seconds</div>';

//exit();

//$sql = "select pin.note, pin.id from tbl_communications com join tbl_post_initiatives pi on com.post_initiative_id = pi.id join tbl_post_initiative_notes pin on pin.id = com.note_id where communication_date >= '2008-08-01 00:00:00' and pi.initiative_id > 1 order by com.id";
//$sql = "select pin.note, pin.id from tbl_communications com join tbl_post_initiatives pi on com.post_initiative_id = pi.id join tbl_post_initiative_notes pin on pin.id = com.note_id where pin.id = 1248682 order by com.id";

//------------- show records -----------
//$sql = 'select pin.note, pin.id from tbl_communications com join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
//		'join tbl_post_initiative_notes pin on pin.id = com.note_id where pin.id in (' . implode($bad_records, ',') . ') order by com.id';
//
//echo "<p>$sql</p>";
//$results = $db->get_results($sql, ARRAY_A);
//foreach ($results as $row)
//{
//	$str = $row['note'];
//	echo '<p>' . $row['id'] . ' : ' . $str . '</p>';
//}	
//---------------------------------------


function checkForBadCharacter($str)
{
	$bad_hex_values = getBadCharsArray();
	
//	echo $str . '<br />';
	$arr = str_split($str);
//	$arr = mbStringToArray($str);
	
	foreach ($arr as $item)
	{
		if (in_array(bin2hex($item), $bad_hex_values))
		{
//			echo 'Found bad character: '  . $item . '<br />';
			return true;
		}
	}
}

function checkForBadSequence($str)
{
	$bad_hex_sequences = getBadCharSequences();
	$out_str = '';

	$arr = str_split($str);
//	$arr = mbStringToArray($str);
	
	foreach ($arr as $item)
	{
		$out_str .= bin2hex($item) . ':';
	}
	
	//echo $out_str . '<br />';
	foreach ($bad_hex_sequences as $key => $item)
	{
//		echo $item;
		if (strpos($out_str, $key) > 0)
		{
			return true;	
		}
	}
	
}

function correctBadSequences($str)
{
	echo 'orig str: ' . $str . '<br /><br />';
	$bad_hex_sequences = getBadCharSequences();
	
	$str_out = '';

	$arr = str_split($str);
	
	foreach ($arr as $item)
	{
		$str_out .= bin2hex($item) . ':';
	}
//	echo 'orig $str_out: ' . $str_out . '<br /><br />';
	foreach ($bad_hex_sequences as $item => $key)
	{
//		echo "$key : $item<br/>"; 
		$str_out = str_ireplace($item, $key, $str_out);
	}
	
//	echo 'new $str_out: ' . $str_out . '<br />';
	
	//remove trailing : from $str_out as it creates an blank char at the end of sequence which is then translated into a ?
	$str_out = rtrim($str_out, ':');
	
	$arr_out = explode(':', $str_out);
	$str_out = '';
	foreach ($arr_out as $item)
	{
		if ($item != '0')
		{
//			echo "$item : " . hexdec($item) . "<br />";
			$str_out .= chr(hexdec($item));
		}
	}
//	$str_out = str_ireplace(':', '', $str_out);
	
//	echo 'final $str_out: ' . $str_out . '<br /><br />';
	
	return $str_out;
}

//function mbStringToArray ($string) 
//{
//    $strlen = mb_strlen($string);
//    echo $strlen;
//    while ($strlen) {
//        $array[] = mb_substr($string,0,1,"ISO-8859-1");
//        $string = mb_substr($string,1,$strlen,"ISO-8859-1");
//        $strlen = mb_strlen($string);
//    }
//    return $array;
//}
function getBadCharSequences()
{
	//$bad_hex_sequences = array('27' => 'e2:80:98',
	//											'26' => 'e2:80:99');
												
	$bad_hex_sequences = array('e2:80:98' => '27',
												'e2:80:99' => '27');
												
	return $bad_hex_sequences;
}

function getBadCharsArray()
{
$bad_hex_values = array(
'e2',
'80', 
'99',
'c3',
'83',
'c2',
'a2',
'c3',
'a2',
'9a',
'9c',
'c2',
'ac',
'c3',
'a2',
'9e',
'c2',
'a2',
'E2',
'C2',
//'27',
'A1',
'A2',
//'A3',
'A4',
'A5',
'A6',
'A7',
'A8',
'A9',
'AA',
'AB',
'AC',
'AD',
'AE',
'AF',
'B0',
'B1',
'B2',
'B3',
'B4',
'B5',
'B6',
'B7',
'B8',
'B9',
'BA',
'BB',
'BC',
'BD',
'BE',
'BF',
'C0',
'C1',
'C2',
'C3',
'C4',
'C5',
'C6',
'C7',
'C8',
'C9',
'CA',
'CB',
'CC',
'CD',
'CE',
'CF',
'D0',
'D1',
'D2',
'D3',
'D4',
'D5',
'D6',
'D7',
'D8',
'D9',
'DA',
'DB',
'DC',
'DD',
'DE',
'DF',
'E0',
'E1',
'E2',
'E3',
'E4',
'E5',
'E6',
'E7',
'E8',
'E9',
'EA',
'EB',
'EC',
'ED',
'EE',
'EF',
'F0',
'F1',
'F2',
'F3',
'F4',
'F5',
'F6',
'F7',
'F8',
'F9',
'FA',
'FB',
'FC',
'FD',
'FE',
'FF');

return $bad_hex_values;
}

?>
</body>
</html>