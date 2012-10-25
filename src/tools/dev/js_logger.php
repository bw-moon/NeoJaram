<?
/*
 * 웹으로 데이터를 받아 로그로 남겨주는 역할
 * by 18th 문병원 
 */
require_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
$js_logger = getLogger("JsLogger");
$log_str = sprintf("\nFROM : %s\nDATA : %s", $_GET['path'], $_GET['str']);
$js_logger->debug($log_str);
?>
