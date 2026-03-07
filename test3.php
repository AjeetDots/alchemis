<?php
$c = file_get_contents('temp_img2.png');
echo strlen($c) . " bytes\n";
echo "Start hex: " . bin2hex(substr($c, 0, 80)) . "\n";
$lines = explode("\r\n", $c);
foreach(array_slice($lines, 0, 15) as $line) {
   echo substr($line, 0, 100) . "\n";
}
