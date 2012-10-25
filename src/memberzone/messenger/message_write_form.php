<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-05-21
 * Last Update : 2003-05-21
 *************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();

echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?".">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jaram 3rd Renewal : 2003</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ko-KR" />
<meta name="Generator" content="EditPlus" />
<meta name="Author" content="Neo Jaram Makers" />
<meta name="Description" content="한양대학교 전자컴퓨터공학부 전산전공학회 자람입니다." />
<!-- 공통 스타일 시트 -->
<link rel="stylesheet" type="text/css" href="/css/main.css" />

<script language="javascript" type="text/javascript">
function cancel ()
{
	window.close();
}
function change_location(a)
{
	window.location="message_write_form.php?group=" + a.options[a.selectedIndex].value;
}
function msg_send_submit()
{
	if( window.document.memo.id.value == "0" )
	{
		alert('qnpfr');
		//memo.
	}
	else
		window.document.memo.submit();
}
</script>
</head>

<body bgcolor="#f1f1f1" link="#1166bb" vlink="#666666" alink="#ddeeff">
<form name="memo" method="post" action="message_write.php">
<table width="100%" cellspacing="0" cellpadding="5" class="text">
	<tr>
		<td colspan="2"><div class="SubMenu">Send Message</div></td>
	</tr>
	<tr>
		<td valign="top" width="30" align="right">To</td>
		<td>
			<select name="group" onChange='change_location(this);' class="text">
<?
	echo "\t\t\t\t<option value=\"0\" " . $str . " >그룹을 선택하세요\n";
	echo "\t\t\t\t<option value=\"r_receive\" " . ($_GET['group']=="r_receive"?"selected":""). " >최근주소록/받은메시지\n";
	echo "\t\t\t\t<option value=\"r_send\" " . ($_GET['group']=="r_send"?"selected":"") . " >최근주소록/보낸메시지\n";
	//echo "\t\t\t\t<option value=\"0\" " . $str . " >기수를 선택하세요\n";

	$result = mysql_query("select gid, group_name from jaram_groups where ( gid >= 1000 and gid < 1300 ) or ( gid >= 3000 and gid < 5000 ) order by gid");
	//$result = mysql_query("select distinct user_number from jaram_users order by user_number");

	while($field = mysql_fetch_array($result))
	{
		$str = "";
		if( $_GET['group'] == $field['gid'] )
		{
			$str = "selected";
			$group_name = $field['group_name'];
		}
		echo "\t\t\t\t<option value=\"" . $field['gid'] . "\" " . $str . " >" . $field['group_name'] . "\n";
		//echo "\t\t\t\t<option value=\"" . $field[0] . "\" " . $str . " >" . $field[0] . "기\n";
	}
?>
			</select><br/>
			<img src="/images/t.gif" width="1" height="4"/><br/>
			<select name="id" class="text">
<?
	if( $_GET['group'] )
	{
		if( $_GET['group'] == "r_send" )
			$query = "SELECT u.uid, u.user_name, u.user_id, m.receive, m.send FROM jaram_web_messenger AS m LEFT  JOIN jaram_users AS u ON ( m.receive = u.uid ) WHERE m.send = " . $_SESSION["jaram_user_uid"] . " AND m.delete_S =  'N' AND m.send_time > " . (time() - 604800/* 60 * 60 * 24 * 7 == 1 week */) . " GROUP BY m.receive ORDER  BY m.send_time DESC LIMIT 10";
		else if ( $_GET['group'] == "r_receive" )
			$query = "SELECT u.uid, u.user_name, u.user_id, m.receive, m.send FROM jaram_web_messenger AS m LEFT  JOIN jaram_users AS u ON ( m.send = u.uid ) WHERE m.receive = " . $_SESSION["jaram_user_uid"] . " AND m.delete_R =  'N' AND m.send_time > " . (time() - 604800/* 60 * 60 * 24 * 7 == 1 week */) . " GROUP BY m.send ORDER  BY m.send_time DESC LIMIT 10";
		else
			$query = "select g.gid, u.uid, u.user_id, u.user_name from jaram_user_group as g left join jaram_users as u USING(uid) where g.gid = " . $_GET['group'] . " order by u.user_name";
	}
	else
		$query = "select uid, user_id, user_name from jaram_users order by user_name";

/*
	$query = "select uid, user_id, user_name from jaram_users";
	if( $_GET['group'] )
		$query .= " where user_number = " . $_GET['group'];
	$query .= " order by user_name";
*/

	$result = mysql_query($query);

	$ids = "";
	echo "\t\t\t\t<option value=\"0\">유저를 선택하세요\n";

	while($field = mysql_fetch_array($result))
	{
		$str = "";
		if( $_GET['uid'] == $field['uid'] )
		{
			$str = "selected";
		}
		echo "\t\t\t\t<option value=\"" . $field['uid'] . "\" " . $str . ">" . $field['user_name'] . "(" . $field['user_id'] . ")\n";
		$ids .= $field['uid'] . ":";
	}
	$ids = substr($ids, 0, -1);

	if( $_GET['group'] )
	{
		if( $_GET['group'] != "r_send" && $_GET['group'] != "r_receive" )
			echo "\t\t\t\t<option value=\"" . $ids . "\" selected>" . $group_name . " 전체\n";
	}
	else
	{
		echo "\t\t\t\t<option value=\"" . $ids . "\">자람 전체\n";
	}
?>
			</select>
		</td>
	</tr>

	<tr>
		<td align="right">Text</td>
		<td>
			<textarea name="passage" rows="14" style="width:100%"></textarea>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center"><a href="javascript:msg_send_submit();"><img src="/images/button/btn_send_msg.gif" border="0" style="cursor:hand;"/></a>&nbsp;<a href="javascript:cancel();"><img src="/images/button/btn_cancel_2.gif" border="0"></a></td>
	</tr>

</table>
</form>
</body>

</html>
