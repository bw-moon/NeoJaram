<?
login_check();
dbconn();

mysql_query("DELETE FROM jaram_custom_menu WHERE uid='".$_SESSION["jaram_user_uid"]."'");

$values = explode(",", $_POST['custom_menus']);

for ($i = 0; $i < count($values); $i++){ 
	$ID = explode(":", $values[$i]);
	$pid = $ID[0];
	$bid = $ID[1];

	// echo "INSERT INTO jaram_custom_menu (uid, pid, bid, order_num) VALUES ( '".$_SESSION["jaram_user_uid"]."', '".$pid."', '".$bid."', '".($i + 1)."')<br>";

	$result = mysql_query("INSERT INTO jaram_custom_menu (uid, pid, bid, order_num) VALUES ( '".$_SESSION["jaram_user_uid"]."', '".$pid."', '".$bid."', '".($i + 1)."')");
} 
echo "Save Custom Menu Complete.";
echo ("<meta http-equiv=refresh content=\"0; url=./?page=custom\" />");
?>