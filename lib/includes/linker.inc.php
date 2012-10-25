<?php
include_once "library.inc.php";

// 자동 로그인 파일
include_once "auto_login.inc.php";

// 게시판 최신글 수를 얻어옴 array(보드네임 => 오늘올린글수);
$BOARD_RECENT_COUNT = recent_board_count();

// 서브메뉴 및 현재메뉴
$PROGRAM_INFO = get_program_info();
$DOCUMENT_SUBMENU = get_menuinfo($PROGRAM_INFO);

// 페이지의 타이틀 <title></title>에 들어갈 내용
if (!empty($DOCUMENT_SUBMENU['sub_menu']))
	$DOCUMENT_TITLE = $DOCUMENT_SUBMENU['sub_menu'];
else 
	$DOCUMENT_TITLE = $DOCUMENT_SUBMENU['main_menu'];

?>