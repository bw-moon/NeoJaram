#!/usr/bin/php4 -q
<?php
include_once (realpath('../../lib/includes')."/library.inc.php");

$c_time[] = microtime();

$fp = fopen("http://jaram.org/board/recent_count.php", "r");

if(!empty($fp)) {
	$rdf = "";
	while (!feof($fp)) {
		$rdf .= fread($fp, 1024);
	}
}
$handle = fopen($homepage_path."/recent_count.txt", 'w');
fwrite($handle, $rdf);
fclose($handle);

$c_time[] = microtime();

$excute_time = get_microtime($c_time[0], $c_time[1]);

echo ("elapsed time = ".$excute_time." seconds\n");
?>
