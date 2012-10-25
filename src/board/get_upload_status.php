<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once "include/setup.php";
require_once "include/info.php";
// 임시 업로드 폴더
$UPLOAD_DIR_TEMP=$UPLOAD_DIR.DIRECTORY_SEPARATOR."temp";

if ($_SESSION['board_upload_now']) {
	list($last_uploaded_file, $last_upload_path) = explode("|", $_SESSION['board_upload_now']);
	$GLOBALS['logger']->debug("{$last_uploaded_file} {$_GET['temp_file_name']}");
	if ($last_uploaded_file != $_GET['temp_file_name']) {
		$GLOBALS['logger']->debug("reset upload session data");
		unset($_SESSION['board_upload_now']);
		unset($_SESSION['board_upload_start_time']);
        unset($_SESSION['board_upload_start_size']);
	}
}

if ($_GET['get_only_filesize']) {
	$size = filesize($UPLOAD_DIR_TEMP.DIRECTORY_SEPARATOR.$_GET['temp_file_name']);
	$GLOBALS['logger']->debug("size : {$size}");
	echo $size;
	exit;
}

if (file_exists($UPLOAD_DIR_TEMP.DIRECTORY_SEPARATOR.$_GET['temp_file_name'])) {
	$size = getFancyFileSize(filesize($UPLOAD_DIR_TEMP.DIRECTORY_SEPARATOR.$_GET['temp_file_name']));
	$time = time() - substr($_GET['temp_file_name'], 0, -3);
	echo "upload complete (size : {$size}, takes {$time}s) ";

    $GLOBALS['logger']->debug("upload complete, file : {$_GET['temp_file_name']}");
	
    unset($_SESSION['board_upload_now']);
	unset($_SESSION['board_upload_start_time']);
    unset($_SESSION['board_upload_start_size']);
} else {	
	$GLOBALS['logger']->debug("now uploading, file : ".$UPLOAD_DIR_TEMP.DIRECTORY_SEPARATOR.$_GET['temp_file_name']);
	$tmp_files = array();
	$last_uploaded_path = "";

	if (!$_SESSION['board_upload_now']) {
		$_SESSION['board_upload_now'] = $_GET['temp_file_name']."|".array_shift(glob(ini_get('upload_tmp_dir').DIRECTORY_SEPARATOR."*.tmp"));

        list($last_uploaded_file, $last_uploaded_path) = explode("|", $_SESSION['board_upload_now']);

        $_SESSION['board_upload_start_time'] = time();
        $_SESSION['board_upload_start_size'] = filesize($last_uploaded_path);
		$GLOBALS['logger']->debug($_SESSION['board_upload_now']);
	}

	$start_time  = substr($_GET['temp_file_name'], 0, -3);
	
    if (time() > $start_time) {
		$speed = getFancyFileSize((filesize($last_uploaded_path)/1)/(time()-$start_time))."/s";
		$GLOBALS['logger']->debug("{$speed} {$start_time} ".time());
	} else {
		$GLOBALS['logger']->debug("{$start_time} ".time());
	}

	$size = getFancyFileSize(filesize($last_uploaded_path));

    // 5초동안 파일 크기 변화가 없으면 완료된 것으로 간주.
    if (time() > $_SESSION['board_upload_start_time'] + 5 && $_SESSION['board_upload_start_size']  >= filesize($last_uploaded_path)) {
        echo "upload complete (업로드에 실패했습니다) ";
        exit;
    }

	echo "now uploading (size : {$size}) {$speed}";
}
