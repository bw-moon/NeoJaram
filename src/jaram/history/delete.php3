<?
/*
 * Jaram > History
 * by 21th 조요한
 */
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");

?>

<?
	dbconn();

	$dbdel="delete from jaram_history where year=$year and month=$month";
	$result=mysql_query($dbdel);

	echo"	<meta http-equiv='Refresh' content='0; URL=index2.php'>	";

include_once INCLUDE_PATH."/footer.inc.php";
?>