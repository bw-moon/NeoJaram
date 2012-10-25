<?
login_check();
dbconn();

if ($_POST['bookmark_url'] && $_POST['bookmark_title']) {
	$query = "UPDATE jaram_bookmark SET bookmark_url = '$_POST[bookmark_url]', bookmark_title = '$_POST[bookmark_title]' WHERE id = '$_POST[id]';";
	mysql_query($query);
}

$query = "SELECT * FROM jaram_bookmark WHERE id = '$_GET[bookmark_id]' AND uid = '$_SESSION[jaram_user_uid]' LIMIT 1;";
$result = mysql_query($query);

//echo $query;
//mysql_query($query);
?>
<form method="post">
Bookmark Url:<br/>
<input type="text" name="bookmark_url" size="50" value="<?=mysql_result($result, 0, "bookmark_url");?>"/>
<br/><br/>
Bookmark Title:<br/>
<input type="text" name="bookmark_title" size="50" value="<?=mysql_result($result, 0, "bookmark_title");?>"/>
<br/><br/>
<input type="hidden" name="id" value="<?=mysql_result($result, 0, "id");?>"/>
<input type="submit" class="text" value=" submit form "/>
</form>
<a href="/">Back to your homepage</a>
