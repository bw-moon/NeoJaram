<?
/*
 * Jaram > History
 * by 21th 조요한
 */

include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");

?>


<?
	dbconn();
	$contents=	realsafehtml($contents);
    $contents= nl2br($contents);

	$dbinsert = "insert into jaram_history values ('$year ','$month','$contents')";
	$result=mysql_query($dbinsert);

	echo"	<meta http-equiv='Refresh' content='0; URL=index2.php'>	";
	
include_once INCLUDE_PATH."/footer.inc.php";
?>