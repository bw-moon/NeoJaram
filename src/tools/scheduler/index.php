<?
/*
 * 일정관리 프로그램 using cache
 * by 18th 문병원 
 */
require_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");

$import_js = "{$web_abs_path}/js/navigator.js";
require_once INCLUDE_PATH."/header.inc.php";
require_once "libarary.schedule.php";
require_once "show_schedule.php";

//$t = elapsed($start);

/*
$a=chk_auth("auth_edit");

if($a==true)
	echo "true";
else
	echo "false";
*/
//echo("<br />elapsed time = $t seconds\n");
require_once INCLUDE_PATH."/footer.inc.php";
?>