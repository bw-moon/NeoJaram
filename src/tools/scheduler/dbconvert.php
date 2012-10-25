<?
$connect = mysql_connect("localhost", "root", "dndjdeostm") or die("<b>MySQL접속에 실패했습니다</b>: " . mysql_error());

mysql_select_db("neojaram", $connect) or die("<b>DB연결에 실패했습니다</b>: " . mysql_error());

$query = "SELECT * FROM jaram_scheduler_tmp ORDER BY no ASC;";
$result = mysql_query($query);
$count = 0;

while($data = mysql_fetch_array($result))
{
	$data[month] = sprintf("%02d", $data[month]);
	$data[day] = sprintf("%02d", $data[day]);

	$start = $data[year].$data[month].$data[day];
	$period =  $data[year].$data[month];

	$year = $data[year];
	$month = $data[month];
	$day = $data[day];
	$hour = 12;
	$min = 0;
	$mdate = mktime($hour, $min, 0, $month, $day, $year);

	$query_2 = "INSERT INTO `jaram_schedule` VALUES('', 4000, '$data[subject]', '$data[comment]', '$start', '$period', '$mdate', '0', '1000', '0', '0000');";

	//echo $query_2."<br><br>";

	
	if (mysql_query($query_2))
	{
		$count++;
		echo $count."번째 데이터가 성공적으로 입력되었습니다<br/>";
	}
	

}

mysql_close();
?>