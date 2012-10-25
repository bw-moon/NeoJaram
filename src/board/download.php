<?php
ob_start();
include_once('include/info.php');
chk_auth('read');
$dbo = ZendDB::getDBO();
$config = JaramConfig::getConfig();

$fileData = $dbo->fetchRow("select * from jaram_board_file where file_id=:id", array('id'=>$_REQUEST['fileid']));
$bid = $dbo->fetchOne("SELECT bid FROM jaram_board WHERE id=:id", array('id'=>$fileData['sub_id']));
$file = $UPLOAD_DIR.DIRECTORY_SEPARATOR.$bid.DIRECTORY_SEPARATOR.$fileData['file_link'];
$logger->info("download file : {$fileData['file_name']} : {$file}");
ob_end_clean();

if (file_exists($file)) 
{ 
    require_once "HTTP/Download.php";
    require_once "MIME/Type.php";

    $dl = &new HTTP_Download();
    $dl->setFile($file);
    $dl->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT, $fileData['file_name']);
    $dl->guessContentType();
    $dl->send();
 	$dbo->query("update jaram_board_file set file_count=file_count+1 where file_id=:id",  array('id'=>$_REQUEST['fileid']));
} 
else 
{ 
	echo "그런 파일은 서버에 존재하지 않습니다.";
} 
?> 