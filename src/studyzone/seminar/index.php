<?php
/*
 * Study Zone > Seminar 메뉴 메인
 * by 18th 문병원 
 */
include_once (realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");

if (!isset($_GET['year'])) {
	$logger->debug($_SERVER['PHP_SELF']."?year=".date("Y"));
	p_redirect($_SERVER['PHP_SELF']."?year=".date("Y"));
}

include_once ("seminar_list.inc.php");
include_once (INCLUDE_PATH."/footer.inc.php");
?>