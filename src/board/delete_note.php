<?php
require_once "include/setup.php";
require_once "include/info.php";
require_once "include/check.php";
require_once INCLUDE_PATH."/linker.inc.php";

$engine = new JaramBoard($_REQUEST['tableID']);

if ($_REQUEST['action'] == "delete_draft_article") {
	echo "delete draft article...";
	flush();
	$engine->cleanDraftArticle($_REQUEST['draft_id']);
}
else  {
	echo "delete article...";
	flush();
	$engine->deleteArticle($_REQUEST['id']);
}

p_redirect("./bbs.php?tableID={$_REQUEST['tableID']}&pageID={$_REQUEST['superid']}");


// todo : 암호로 삭제하는 코드를 넣을지 말지 고민. @070919 by 문병원

?>