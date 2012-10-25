<?
require "include/setup.php";
include "include/info.php";

// 스마티
$smarty = new JaramSmarty(); 
$smarty->caching=false;

// _GET, _POST
if ($HTTP_GET_VARS['tableID'])
	$tableID=$HTTP_GET_VARS['tableID'];
// 잘못된 게시물 ID값이 없을 경우
if (!$HTTP_GET_VARS['id'])
{
	$smarty->display("error.tpl");
	exit;
}
else
	$id=$HTTP_GET_VARS['id'];

if (!$smarty->is_cached($tableID."/thumbnail.tpl",$tableID."|thumbnail|".$id))
{
	// tableID 체크
	include "include/check.php";
	
	// 썸네일(원하다면 확대도 되지만 ㅡㅡ;;) 지원시만 사용가능
	if ($USING_PREVIEW_IMG!="true")
	{
		$smarty->display("error.tpl");
		exit;
	}

	$dbFile=new DB_mySql;
	$dbFile->query("select * from $M_TABLE_FILE where sub_id=".$id);
	$numberFile=$dbFile->number();
	for ($tempCount=0;$tempCount<$numberFile;$tempCount++)
	{
		$dbFile->seek($tempCount);
		$fileData=$dbFile->fetch_object();
		$tempFileBaseName=explode(".",$fileData->file_name);
		$tempFileBaseNameLocate=sizeof($tempFileBaseName)-1;
		if (!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"gif") ||
			!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"jpg") ||
			!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"png") )
		{
			$imgData[$fileData->file_id]=1;
		}
	}
	$dbFile->close();
	unset($dbFile);
	$smarty->assign("tableID",$tableID);
	$smarty->assign("imgData",$imgData);
}

// 한달단위 캐시
$smarty->cache_lifetime=2592000;

$smarty->display($tableID."/thumbnail.tpl",$tableID."|thumbnail|".$id);

?>