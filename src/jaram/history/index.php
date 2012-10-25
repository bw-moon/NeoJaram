<?php
/*
 * Jaram 메뉴 메인
 * by 18th 문병원 
 */
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");

$smarty = new JaramSmarty();
$smarty->display('jaram/history_view.tpl');

include_once INCLUDE_PATH."/footer.inc.php";
?>