<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-06-23
 * Last Update : 2003-06-23
 *************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; 

function getTimeString($time)
{
	$s_time = date("Y-/m-/d /H:/i:/s", $time);
	$c_time = date("Y-/m-/d /H:/i:/s", time());

	$s_time = split("/", $s_time);
	$c_time = split("/", $c_time);

	for( $i = 0; $i < count($s_time); $i++)
	{
		if( $s_time[$i] == $c_time[$i] )
			continue;
		else
		{
			$str = "";
			for( $j = $i; $j < count($s_time); $j++)
			{
				$str .= $s_time[$j];
			}
			return $str;
		}
	}
}


if( $_SESSION["jaram_user_uid"] )
{
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
<link rel="stylesheet" type="text/css" href="<?=WEB_ABS_PATH?>/css/main.css" />

<script language="javascript" type="text/javascript">
function window_close()
{
	window.close();
}
</script>
</head>

<body bgcolor="#f1f1f1" link="#1166bb" vlink="#666666" alink="#ddeeff">
<form name="sms_list" method="post">
<table width="100%" cellspacing="0" cellpadding="5" class="text">
	<tr>
		<td><div class="SubMenu">Send Message List</div></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="3" class="text">
<?
		$query = "SELECT m.id, u.user_id, u.user_name, m.is_read, m.read_time from jaram_web_messenger AS m LEFT JOIN jaram_users AS u ON u.uid = m.receive WHERE m.send = " . $_SESSION["jaram_user_uid"] . " AND m.delete_S = 'N' ORDER BY m.is_read, m.send_time ASC";
		$result = mysql_query($query);

		while( $field = mysql_fetch_array($result) )
		{
			$is_read = "";
			if( $field['is_read'] != 'N' )
			{
				$time_gap = time() - intval($field['read_time']);
				if( $time_gap < 86400 )
					$is_read = "read " . intval($time_gap/3600) . " hour " . intval(($time_gap%3600)/60) . " minute ago";
				else
					$is_read = "read at " . getTimeString($field['read_time']);
			}
			else
				$is_read = "unread";

			echo "\t<tr><td>\n";
			echo "\t\t<input type=\"checkbox\" name=\"sms\" value=\"".$field['id']."\"/>To:<a href=\"javascript:Msg_read('".$field['id']."')\">".$field['user_name']."(".$field['user_id'].")</a><td>" . $is_read . "</td>\n";
			echo "\t</td></tr>\n";
		}
?>
</table>
<table  width="100%" cellspacing="0" cellpadding="5">
	<tr>
		<td width="50%" align="center" height="("><a href="javascript:On_delete();"><img src="<?=WEB_ABS_PATH?>/images/button/btn_delete.gif" border="0" alt="delete"/></a> <a href="javascript:Select_all();"><img src="<?=WEB_ABS_PATH?>/images/button/btn_select_all.gif" border="0" alt="select"/></a> <a href="javascript:window.close();"><img src="<?=WEB_ABS_PATH?>/images/button/btn_close.gif" border="0" alt="close"/></a></td>
	</tr>
</table>
</form>

<script language="javascript" type="text/javascript">
	// 전체 선택 함수 //
	function Select_all()
	{
		var i;

		if(!document.sms_list.sms)
			return;

		if(!document.sms_list.sms.length)
		{
			document.sms_list.sms.checked = true;
			return;
		}
		if(document.sms_list.sms[0].checked == true)
		{
			for( i = 0; i < document.sms_list.sms.length; i++)
			{
				document.sms_list.sms[i].checked = false;
			} 
		}
		else
		{
			for( i = 0; i < document.sms_list.sms.length; i++)
			{
				document.sms_list.sms[i].checked = true;
			}
		}
	} // end of Select_all

	/* 선택된 값의 value를 리턴해주는 함수 */
	function Select_value()
	{
		var i;
		var result;

		if(!document.sms_list.sms)
			return;
		if(document.sms_list.sms.length) {
			for( i = 0 ; i < document.sms_list.sms.length; i++)
			{
				if(document.sms_list.sms[i].checked == true) 
					if(!result)
						result=document.sms_list.sms[i].value;
					else
						result=result + "," + document.sms_list.sms[i].value;
			} 
		}
		else
			if(document.sms_list.sms.checked ==true)
				result=document.sms_list.sms.value;
		
		return result;
	} // end of Select_value

	/* 선택삭제 버튼을 눌렀을때 */
	function On_delete()
	{
		var index;
		index = Select_value();

		if(!index) {
			alert('삭제할 쪽지를 선택해주세요');
			return;
		}
		else
		{
			hidden_messenger_frame.location.href='<?=WEB_ABS_PATH?>/memberzone/messenger/message_delete.php?id='+index + '&amp;type=send';
			// window.open('/memberzone/messenger/message_delete.php?id='+index + '&amp;type=send');
		}
	} // end of On_delete

	/* 선택한 쪽지 보여주기 */
	function Msg_read(msg_id)
	{
		if(!msg_id)
		{
			alert('메시지가 지워졌거나 오류가 있습니다.');
			return;
		}
		else 
			location.href='<?=WEB_ABS_PATH?>/memberzone/messenger/message_view.php?id=' +msg_id + '&type=send&from=sendlist';
	}
</script>

<div id="hidden_messenger_div" style="display:none;">
	<iframe id="hidden_messenger_frame" href="#"></iframe>
</div>
</body>
</html>
<?
	}
	else
	{
?>
<script language="javascript" type="text/javascript">
	window.close();
</script>
<?
	}
?>
