<?
require_once "include/setup.php";
require_once "include/info.php";

// _GET, _POST
if ($HTTP_GET_VARS['tableID'])
	$tableID=$HTTP_GET_VARS['tableID'];

// 임시 업로드 폴더
//$TEMP_TODAY=date("Ymd");
$UPLOAD_DIR_TEMP=$UPLOAD_DIR.DIRECTORY_SEPARATOR."temp";

// $UPLOAD_DIR년월일 ex) temp
if(!is_dir($UPLOAD_DIR_TEMP))
{
	if (!mkdir($UPLOAD_DIR_TEMP,0755)) {
		$GLOBALS['logger']->error("directory create failed : {$UPLOAD_DIR_TEMP}");
	}
	chmod($UPLOAD_DIR_TEMP,0775);
}

if (is_uploaded_file($HTTP_POST_FILES['file_upload_file']['tmp_name']))
{
	$GLOBALS['logger']->debug($_POST);
	if ($_POST['file_upload_current_file_name']) {
		$newName = $UPLOAD_DIR_TEMP.DIRECTORY_SEPARATOR.$_POST['file_upload_current_file_name'];
	} else {
		$newName = tempnam ($UPLOAD_DIR_TEMP, $tableID);
	}
	if(move_uploaded_file($HTTP_POST_FILES['file_upload_file']['tmp_name'], $newName))
	{
		$GLOBALS['logger']->debug($_POST['file_upload_current_file_name']);
		/*
		// 썸네일 만들기
		$tempFileBaseName=explode(".",$HTTP_POST_FILES[attach_file][name]);
		$tempFileBaseNameLocate=sizeof($tempFileBaseName)-1;
		
		if ($HTTP_POST_VARS[USING_PREVIEW_IMG]=="true" && (
			!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"gif") ||
			!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"jpg") ||
			!strnatcasecmp($tempFileBaseName[$tempFileBaseNameLocate],"png") ) )
		{
			copy_resize_img($newName,$newName."_resize", $HTTP_POST_VARS[PREVIEW_IMG_X], $HTTP_POST_VARS[PREVIEW_IMG_Y], $tempFileBaseName[$tempFileBaseNameLocate]);
		}
		*/
		
		$newName=$TEMP_TODAY."/".basename($newName);
		
		//  파일이름|파일크기|년월일/링크이름		
		$upload_file.=$HTTP_POST_FILES['file_upload_file']['name']."|".$HTTP_POST_FILES['file_upload_file']['size']."|".$newName;
		$GLOBALS['logger']->info("Success : {$upload_file}");
	}
	else
	{
		$GLOBALS['logger']->debug("{$HTTP_POST_FILES[attach_file][name]} : 업로드에 실패했습니다.");
	}
}
else
{
	$GLOBALS['logger']->debug("{$HTTP_POST_FILES[attach_file][name]} : 첨부파일이 올바르게 선택되지 않았습니다.");
}
?>