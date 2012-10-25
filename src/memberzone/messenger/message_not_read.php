<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-05-23
 * Last Update : 2003-05-23
 *************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
dbconn();
	if( $_SESSION["jaram_user_uid"] )
	{
		$arr_id = split(",", $_GET['id']);
		$count = count($arr_id);

		for( $i = 0; $i < $count; $i++)
		{
			@mysql_query("update jaram_web_messenger set is_read = 'F' where id = " . $arr_id[$i] . " and is_read = 'Y' and receive = " . $_SESSION["jaram_user_uid"]);
		}
	}

?>
<script language="javascript" type="text/javascript">
	parent.window.location.reload();
	window.close();
</script>




