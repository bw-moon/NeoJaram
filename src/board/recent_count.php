<?
require "include/setup.php";
include "include/info.php";

$smarty = new JaramSmarty(); 
$smarty->caching=false;

//include "include/check.php";

$dbList = new DB_mySql;
$dbCount = new DB_mySql;
$dbList->query("SELECT name FROM $M_ADMIN_TABLE");
$numberList = $dbList->number();
$today=date("Ymd");
for($tempCount=0;$tempCount<$numberList;$tempCount++)
{
	$dbList->seek($tempCount);
	$resultData=$dbList->fetch_object();
	$fromTable="mboard_".$resultData->name;
	$dbCount->query("SELECT COUNT(id) FROM $fromTable where date>'$today'");
	$listData[$tempCount][0]=$resultData->name;
	$listData[$tempCount][1]=$dbCount->sql_result(0,0);
	if(!$listData[$tempCount][1])
		$listData[$tempCount][1]="0";
}

$smarty->assign("listData",$listData);


if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: text/plain');

$smarty->display("board/recent_count.tpl");

?>