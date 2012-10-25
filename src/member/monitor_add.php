<?
dbconn();

//echo $_SERVER['HTTP_REFERER']."<br/>";

$PROGRAM_INFO = get_program_info($_SERVER['HTTP_REFERER']);

$result = mysql_query("SELECT id FROM jaram_bbs_monitor WHERE (pid = '".$PROGRAM_INFO['pid']."' AND bid = '".$PROGRAM_INFO['bid']."' AND uid = '".$_SESSION['jaram_user_uid']."');");

if (@mysql_num_rows($result) > 0) {
	$SHOW_MESSAGE = $PROGRAM_INFO['sub_menu']."는 <b>이미 모니터중</b>인 BBS입니다.<br/><br/>";
} else {
	$query = "INSERT INTO jaram_bbs_monitor (uid, pid, bid) VALUES ('".$_SESSION['jaram_user_uid']."', '".$PROGRAM_INFO['pid']."', '".$PROGRAM_INFO['bid']."');";
	mysql_query($query);
	$SHOW_MESSAGE = "Add monitor for <b>'".urldecode($_SERVER['HTTP_REFERER'])."'</b> with title <b>'".urldecode($PROGRAM_INFO['sub_menu'])."'</b><br/><br/>";
}
?>

<?=$SHOW_MESSAGE?>

<a href="<?=$_SERVER['HTTP_REFERER']?>">Visit the monitored page</a> - <a href="/">Back to your homepage</a>