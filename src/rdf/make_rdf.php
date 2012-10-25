#!/usr/bin/php4 -q

<?php
include_once (realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");

$c_time[] = microtime();
mysql_connect("localhost", "webteam", "tpfmwlgh");
mysql_select_db("neojaram");

$result = mysql_query("SELECT name FROM jaram_board_admin");

if (@mysql_num_rows($result) > 0)
{
	while ($rows = mysql_fetch_array($result))
	{
		echo ("open file : ". "http://jaram.org/board/rdf.php?tableID=".$rows['name']."\n");
		$fp = fopen("http://jaram.org/board/rdf.php?tableID=".$rows['name'], "r");
		
		if(!empty($fp)) {
			$rdf = "";
			while (!feof($fp)) {
				$rdf .= fread($fp, 1024);
			}
		}

		$handle = fopen($homepage_path."/rdf/".$rows['name'].".xml", 'w');
		fwrite($handle, $rdf);
		fclose($handle);
		echo ("save file : ".$rows['name'].".xml\n\n");
	}
}

$c_time[] = microtime();

$excute_time = get_microtime($c_time[0], $c_time[1]);

echo ("elapsed time = ".$excute_time." seconds\n");

mysql_close();
?>
