<?php
$page_start = microtime();
require_once realpath('../lib/includes')."/library.inc.php";
require_once INCLUDE_PATH."/auto_login.inc.php";

$context_root_flag = true;

$import_css = WEB_ABS_PATH."/css/css2.css";
if (eregi("MSIE.6", $_SERVER['HTTP_USER_AGENT'])) 
{
    $import_css .= ",".WEB_ABS_PATH."/css/ie6.css";
}

// 헤더
$header_tpl = new JaramSmarty();
$header_tpl->assign('document_title', "Jaram - Hanyang University Software Study Group");
$header_tpl->assign('layout_style', 'index');
$header_tpl->assign('import_css', $import_css);
$header_tpl->display('common/header.tpl');


if (! is_login() || $_GET['page'] == "front") 
{
	require_once INCLUDE_PATH."/front_page.inc.php";
}
else
{
	require_once INCLUDE_PATH."/personal_page.inc.php";
}

require_once INCLUDE_PATH."/footer.inc.php";


//$logger->debug($_SERVER);
?>