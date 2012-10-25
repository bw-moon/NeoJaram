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
		makeRSS($rows[0]);
		
	}
}


mysql_close();




function chkUpdate($file_mdate, $data_mdate) {
	

}


function makeRSS($table_id) {
		$c_time[] = microtime();
		echo ("open file : ". "http://jaram.org/board/rdf.php?tableID=".$table_id."\n");
		$fp = fopen("http://jaram.org/board/rss.php?tableID=".$table_id, "r");
		
		if(!empty($fp)) {
			$rss = "";
			while (!feof($fp)) {
				$rss .= fread($fp, 1024);
			}
		}

		$handle = fopen(HOMEPAGE_PATH."/rss/".$table_id.".xml", 'w');
		fwrite($handle, $rss);
		fclose($handle);
		echo ("save file : ".$table_id.".xml\n\n");
		$c_time[] = microtime();
		$excute_time = get_microtime($c_time[0], $c_time[1]);
		echo ("elapsed time = ".$excute_time." seconds\n");
}
?>
