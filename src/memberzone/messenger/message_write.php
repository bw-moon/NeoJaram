<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-05-23
 * Last Update : 2003-06-23
 *		lovelyk2 : jaram_web_message 의 count 에 들어가는 값을 변경.  :: count($arr_id) => count($arr_id) * 2
 *************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();
	if( intval($_SESSION["jaram_user_uid"]) )
	{
		$arr_id = split(":", $_POST['id']);
		$count = count($arr_id) * 2;		// 받는 사람 보낸 사람이 모두 지워야 하므로 * 2 만큼 지워지면 됨.

		$passage = htmlspecialchars($passage);
		mysql_query("insert into jaram_web_message (message, count) values('" . $passage . "', " . $count . ")");
		$message = mysql_result(mysql_query("select id from jaram_web_message order by id desc limit 1"), 0);

		for( $i = 0; $i < $count; $i++)
		{
			mysql_query("insert into jaram_web_messenger (send, receive, send_time, message) values(" . $_SESSION["jaram_user_uid"] . ", " . $arr_id[$i] . ", '" . time() . "', " . $message . ")");
		}
	}
?>
<script language="javascript" type="text/javascript">
	opener.window.location.reload();
	window.close();
</script>

