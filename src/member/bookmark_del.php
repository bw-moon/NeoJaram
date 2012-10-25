<?
login_check();
dbconn();

$query = "DELETE FROM jaram_bookmark WHERE id = '$_GET[bookmark_id]' AND uid = '$_SESSION[jaram_user_uid]' LIMIT 1;";

//echo $query;
mysql_query($query);
?>
bookmark deleted<br/><br/>

<a href="/">Back to your homepage</a>