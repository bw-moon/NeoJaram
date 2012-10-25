<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-06-23
 * End Date : 2003-06-23
 *************************/
require_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();
?>
<!-- Jaram Messenger by lovelyk2 -->
<div id="messenger" class="side_container">
<h2>Jaram Messenger</h2>
<form name="sms_list" id="sms_list">
<ul id="messages">
<?
	$query = "select m.id, u.user_id as sender_id, u.user_name as sender_name, m.is_read from jaram_web_messenger as m left join jaram_users as u on u.uid = m.send where m.receive = " . $_SESSION["jaram_user_uid"] . " and m.delete_R = 'N' order by m.send_time";

//	echo $query;

	$result = mysql_query($query);

	$chk_count = 0;
	while( $field = mysql_fetch_array($result) )
	{
		if( $field['is_read'] == 'Y' )
			$is_read = " style=\"color:#999999;\"";
		else
			$is_read = "";

		echo "<li>\n";
		echo "<input type=\"checkbox\" name=\"sms\" value=\"".$field['id']."\"/><a href=\"javascript:Msg_read('".$field['id']."')\"  ".$is_read.">".$field['sender_name']."(".$field['sender_id'].")</a>\n";
		echo "\t</li>\n";
		$chk_count++;
	}

	if( !$chk_count )
	{
		echo "\t<li>\n";
		echo "No Messages";
		echo "\t</li>\n";
	}
?>
</ul>
<a href="javascript:Send_msg();"><img src="<?=WEB_ABS_PATH?>/images/button/btn_send_msg.gif" alt="메세지 보내기" title="메세지 보내기" border="0"/></a>
<?
if(!empty($chk_count)) 
	echo "<a href=\"javascript:Select_all()\"><img src=\"".WEB_ABS_PATH."/images/button/btn_select_all.gif\" border=\"0\" alt=\"전체선택\" title=\"전체 선택\"/></a>"; 
else 
	echo "<a href=\"javascript:Receipt_Certify_msg();\"><img src=\"".WEB_ABS_PATH."/images/button/btn_check_msg.gif\" border=\"0\" alt=\"수신확인\" title=\"수신 확인\"/>";
?>
<? 
if(!empty($chk_count)) echo "
<a href=\"javascript:On_delete();\"><img src=\"".WEB_ABS_PATH."/images/button/btn_delete.gif\" border=\"0\" alt=\"메세지 삭제\"/></a>
<a href=\"javascript:Not_Read();\"><img src=\"".WEB_ABS_PATH."/images/button/btn_unmark.gif\" border=\"0\" alt=\"안읽음표시\"/></a>
<a href=\"javascript:Receipt_Certify_msg();\"><img src=\"".WEB_ABS_PATH."/images/button/btn_check_msg.gif\" border=\"0\" alt=\"수신확인\"/></a>
";
?>
</form>
<div id="hidden_message_div" style="display:none;"><iframe id="hidden_message_frame" href="#"></iframe></div>
</div>

<!--  End of Jaram Messenger -->
