<?
require "include/setup.php";
include "include/info.php";

// FILE BOARD 테이블 이름에 대한 명시를 스스로 하기 때문에 유의해야 한다.
// 자주 호출되는 이상, check.php 파일은 include 하지 않는다.
@$dbFile=new DB_mySql;
@$dbFile->query("select file_name,file_link,file_size from mboard_".$HTTP_GET_VARS[tableID]."_file where file_id=".$HTTP_GET_VARS[fileid]);
@$fileData=$dbFile->fetch_object();
// 리사이즈된 이미지를 원할경우
if($HTTP_GET_VARS[resize])
	$fileData->file_link.="_resize";

header("Content-type: image/png");
header("Pragma: no-cache");
header("Expires: 0");
Header("Content-Disposition: inline; filename=".$fileData->file_name); 
//$MY_URL = "jaram.org";
//if(substr($_SERVER[HTTP_REFERER], 0, strlen($MY_URL)) == $MY_URL)
if(true)
{
	if (is_file($UPLOAD_DIR."/".$HTTP_GET_VARS[tableID]."/".$fileData->file_link)) 
	{
		$fp = fopen($UPLOAD_DIR."/".$HTTP_GET_VARS[tableID]."/".$fileData->file_link, "r");
		if (!fpassthru($fp))
			fclose($fp);
	} 
	else 
	{
		$fp = fopen($UPLOAD_DIR."/../img/noimg.png", "r");
		if (!fpassthru($fp))
			fclose($fp);
	}
}
else
{
	$fp = fopen($UPLOAD_DIR."/../img/errorimg.png", "r");
	if (!fpassthru($fp))
		fclose($fp);
}
?>