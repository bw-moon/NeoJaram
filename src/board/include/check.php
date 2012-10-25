<?php
ob_start();

$tableID = $_REQUEST['tableID'];

// 한 ID당 딸린 TALBE 3개
// 메인 테이블
//$M_TABLE="mboard_{$tableID}";
$M_TABLE = "jaram_board";

// 답글 테이블
//$M_TABLE_RE="mboard_{$tableID}_re"; 
$M_TABLE_RE = "jaram_board_comment";

// 카테고리 테이블
//$M_TABLE_CATEGORY="mboard_{$tableID}_category";
$M_TABLE_CATEGORY = "jaram_board_category";

// 파일 테이블
//$M_TABLE_FILE="mboard_{$tableID}_file";
$M_TABLE_FILE = "jaram_board_file";

$dbo = ZendDB::getDBO();
$checkMainDb= $dbo->fetchAll("SHOW TABLES LIKE :table", array('table'=>$M_TABLE));
$board_pref = $dbo->fetchRow("SELECT * FROM {$M_ADMIN_TABLE} WHERE name=:board_id", array('board_id'=>$tableID));

if (!$checkMainDb || !$board_pref) {
	$smarty->display("error.tpl");
} else {
	// 사용하는 템플릿
	$_TEMPLATE = $board_pref['template'];
	// 한 페이지당 목록 갯수
	$VIEW_SCALE = $board_pref['view_scale'];
	// 페이지 리스트 수
	$VIEW_PAGE_SCALE = $board_pref['view_page_scale'];
	// 카테고리 이용 여부
	$USING_CATEGORY= $board_pref['using_category'];
	// 파일 업로드 이용 여부
	$USING_FILE= $board_pref['using_file'];
	// 코멘트 이용 여부
	$USING_COMMENT=$board_pref['using_comment'];
	// 최신 코멘트 별 정렬
	$USING_COMMENT_SORT=$board_pref['using_comment_sort'];
	// 답변 구조 이용 여부
	$USING_REPLY=$board_pref['using_reply'];
	// 캐시에 직접 접근하는 방식의 카운터 사용 여부
	$USING_DIRECT_COUNTER=$board_pref['using_direct_counter'];
	// 캐시에 직접 접근하는 방식의 코멘트 갯수 사용 여부
	$USING_DIRECT_COMMENT=$board_pref['using_direct_comment'];
	// 게시물 볼때 게시물 list 보기 여부
	$USING_DISPLAY_LIST=$board_pref['using_display_list'];
	// 게시물 LIST에서 첨부파일 정보도 얻어 오기
	$USING_DISPLAY_IMG=$board_pref['using_display_img'];
	// 작은 이미지(이미지 크기 조정되어진)도 가지고 있기
	$USING_PREVIEW_IMG=$board_pref['using_preview_img'];
	// 크기가 조정된 이미지의 원하는 width/height의 최대 크기
	if($USING_PREVIEW_IMG=="true")
	{
		$PREVIEW_IMG_X=$board_pref['preview_img_x'];
		$PREVIEW_IMG_Y=$board_pref['preview_img_y'];
	}

	// 로그인시 유저 세션
	$SESSION_USER_ID=$HTTP_SESSION_VARS[$board_pref['session_id']];
	$SESSION_USER_NAME=$HTTP_SESSION_VARS[$board_pref['session_name']];
	$SESSION_USER_EMAIL=$HTTP_SESSION_VARS[$board_pref['session_email']];
	$SESSION_USER_HOMEPAGE=$HTTP_SESSION_VARS[$board_pref['session_homepage']];

	$GROUP_ID=$board_pref['group'];
}
ob_get_clean();