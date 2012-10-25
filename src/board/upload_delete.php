<?
include_once "include/info.php";

// 삭제 파일명
$UPLOAD_TEMP_FILE=$UPLOAD_DIR."/temp/".$HTTP_POST_VARS["fileTempDel"];

if (is_file($UPLOAD_TEMP_FILE))
	$checkDel=unlink($UPLOAD_TEMP_FILE);

if ($checkDel == true)
{
		echo "
<html><body>
<script language=JavaScript>
</script>
</body></html>
	";
}
else
{
		echo "
<html><body>
<script language=JavaScript>
			window.alert(\"$UPLOAD_TEMP_FILE 삭제파일이 올바르게 선택되지 않았습니다.\");
</script>
</body></html>
	";
}

?>