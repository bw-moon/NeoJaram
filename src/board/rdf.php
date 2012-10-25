<?
require "include/setup.php";
include "include/info.php";

// 스마티
$smarty = new JaramSmarty(); 
$smarty->caching=false;

// _GET, _POST
if ($HTTP_GET_VARS['tableID'])
	$tableID=$HTTP_GET_VARS['tableID'];
if ($HTTP_GET_VARS['count'])
	$maxnum=$HTTP_GET_VARS['count'];

if ($maxnum > 15 || $maxnum < 1)
	$maxnum=10;

include "include/check.php";

$dbList = new DB_mySql;
$dbList->query("SELECT id,title,note,date FROM $M_TABLE order by id DESC limit $maxnum");
$numberList = $dbList->number();

for($tempCount=0;$tempCount<$numberList;$tempCount++)
{
	$dbList->seek($tempCount);
	$listData[$tempCount]=$dbList->fetch_object();
}

switch($tableID)
{
	case "diary":
		$board_name="Jaram Diary";
		$board_note="자람 다이어리";
		break;
	default:
		$board_name=$tableID;
		$board_note="게시판";
}
$zone = date("O");

$smarty->assign("tableID",$tableID);
$smarty->assign("listData",$listData);
$smarty->assign("board_name",$board_name);
#$smarty->assign("board_note",$board_note);
$smarty->assign("nowTime", date("Y-m-d\TH:i:s").$zone[0].$zone[1].$zone[2].":".$zone[3].$zone[4]);
$smarty->assign("zone", $zone[0].$zone[1].$zone[2].":".$zone[3].$zone[4]);

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
header ('Content-Type: text/xml');

$smarty->display("board/rdf.tpl");

?>
