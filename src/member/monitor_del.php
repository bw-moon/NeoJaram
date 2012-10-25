<?
dbconn();

$query = "DELETE FROM jaram_bbs_monitor WHERE id = '$_GET[monitor_id]' AND uid = '$_SESSION[jaram_user_uid]' LIMIT 1;";

//echo $query;
mysql_query($query);
?>
BBS Monitor deleted<br/><br/>

<a href="/">Back to your homepage</a>