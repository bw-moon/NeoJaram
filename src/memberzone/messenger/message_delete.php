<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-05-22
 * Last Update : 2003-05-23 00:59:15
 *************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();

	if( $_SESSION["jaram_user_uid"] )
	{
		$arr_id = split(",", $_GET['id']);

		$count = count($arr_id);

		for( $i = 0; $i < $count; $i++)
		{
			$query = "select message from jaram_web_messenger where id = " . $arr_id[$i];
			$message = @mysql_result(mysql_query($query), 0, 0);
			if( $_GET['type'] == "receive" )
				$query = "update jaram_web_messenger set delete_R = 'Y' where id = " . $arr_id[$i] . " and receive = " . $_SESSION["jaram_user_uid"];
			else if( $_GET['type'] == "send" )
				$query = "update jaram_web_messenger set delete_S = 'Y' where id = " . $arr_id[$i] . " and send = " . $_SESSION["jaram_user_uid"];
			else
			{
?>
<script language="javascript" type="text/javascript">
	window.close();
</script>
<?
				exit();
			}
			@mysql_query($query);
			$affected_rows = mysql_affected_rows();
			@mysql_query("update jaram_web_message set count = count - " . $affected_rows . " where id = " . $message);

			@mysql_query("delete from jaram_web_messenger where delete_R = 'Y' and delete_S = 'Y' ");
			@mysql_query("delete from jaram_web_message where count <= 0 ");
		}
	}

?>
<script language="javascript" type="text/javascript">
	parent.window.location.reload();
	window.close();
</script>

