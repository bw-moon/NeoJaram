<?
include_once (realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");

login_check();
dbconn();

if( !$_GET["gid"] || !$_GET["pid"] || !$_GET["bid"] ) {
	show_error_msg("즐!", "back");
}

$query = "DELETE FROM jaram_auth_access WHERE gid='".$_GET["gid"]."' AND pid='".$_GET["pid"]."' AND bid='".$_GET["bid"]."'";

$result = mysql_query($query);

?>