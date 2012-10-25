<?
function get_user_info($uid) {
	$result = mysql_query("SELECT * FROM jaram_users WHERE uid='".$uid."';");
	$rows = mysql_fetch_array($result);
	return $rows;
}


if ($_POST['action'] == "send") {
	$from = get_user_info($_SESSION['jaram_user_uid']);
	$to = get_user_info($_POST['touser']);
	$content['from_name'] = $_SESSION['jaram_user_name'];
	$content['from_address'] = $from['user_email'];
	$content['to_name'] = $to['user_name'];
	$content['to_address'] = $to['user_email'];
	$content['subject'] = $_POST['subject'];
	$content['message'] = $_POST['body'];

	if (SEND_HTML_MAIL($content)) {
		show_std_msg("메일이 전송되었습니다.\n\n2초후 자동으로 이전 페이지로 이동합니다.", "std", "/?page=sendmessage&amp;touser=".$_POST['touser']);
	} else {
		show_std_msg("메일 전송이 실패하였습니다.\n\n2초후 자동으로 이전 페이지로 이동합니다.", "std", "/?page=sendmessage&amp;touser=".$_POST['touser']);
	}


} else {
?>
메일을 보낼 수 있습니다.<br><br>
<form action="/?page=sendmessage" method="post">
<input type="hidden" name="action" value="send"/>
<input type="hidden" name="touser" value="<?=$_GET['touser']?>"/>
<b>From:</b> <?=$_SESSION['jaram_user_name']?>(<b><?=$_SESSION['jaram_user_id']?></b>)
<br/><br/>

<b>Subject:</b><br/>
<input type="text" name="subject" size="30" maxlength="60" value=""/><br/><br/>

<b>Message:</b><br/>
<TEXTAREA NAME="body" ROWS="20" COLS="60" WRAP="HARD" class="text"></TEXTAREA>
<br/><br/>
<CENTER>
<INPUT TYPE="SUBMIT" NAME="send_mail" VALUE="Send Message"/>
</CENTER>
</form>
<? } ?>