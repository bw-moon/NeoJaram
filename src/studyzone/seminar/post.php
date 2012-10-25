<?
include_once (realpath('../../lib/includes')."/header.inc.php");

dbconn();

if ($_POST['mode'] == "comment" && strlen($_POST['text']) > 0 && (strlen($_POST['name']) > 0 || strlen($_SESSION['jaram_user_uid']) > 0) && is_spam_client()==false)
{
	$result=stristr($_POST['text'],'http://');
	if($result==false)
	{
		$reg_date = time();
		$now_ip=getenv("REMOTE_ADDR");
		$query = "INSERT INTO jaram_seminar_comment (seminar_id, user_id, name, text, reg_date, ip) VALUES ('$_POST[seminarID]', '$_SESSION[jaram_user_uid]', '$_POST[name]', '$_POST[text]', '$reg_date', '$now_ip')";
		mysql_query($query) or die("잘못된 쿼리입니다");
		p_redirect($_SERVER['HTTP_REFERER']);
	}
	else
		show_error_msg("허락되지 않는 문자열이 들어가 있습니다.", "back");
}

else {
	show_error_msg("제대로 입력하지 않았거나 잘못된 접근입니다.", "back");
}

mysql_close();

include (INCLUDE_PATH."/footer.inc.php");
?>