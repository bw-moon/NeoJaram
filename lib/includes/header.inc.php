<?php
$page_start = microtime();
require_once("library.inc.php"); 


// 자동 로그인 파일
require_once("auto_login.inc.php");

// 게시판 최신글 수를 얻어옴 array(보드네임 => 오늘올린글수);
$BOARD_RECENT_COUNT = recent_board_count();


ob_start();
// 서브메뉴 및 현재메뉴
$PROGRAM_INFO = get_program_info();
$DOCUMENT_SUBMENU = get_menuinfo($PROGRAM_INFO);

// 루트의 index 페이지가 아닐 경우
$context_root_flag = false;

// 멤버존에 들어갈 때 권한 체크
// 임시로 새내기 게시판만 아무나 들어갈 수 있도록 했음.
if (($DOCUMENT_SUBMENU['main_menu'] == "Member Zone" && !$_SESSION["jaram_user_id"])
	&& $_GET['tableID'] != "freshmanboard") {
    login_check();
	exit;
}

// 페이지의 타이틀 <title></title>에 들어갈 내용
if ($DOCUMENT_SUBMENU['sub_menu']) {
	$DOCUMENT_TITLE = "Jaram :: ".$DOCUMENT_SUBMENU['sub_menu'];
	if (isset($_GET['tableID']) && (isset($_GET['id']) || isset($_GET['modID']))) {
		$article_id = (isset($_GET['id']))?$_GET['id']:$_GET['modID'];
		$DOCUMENT_TITLE .= " - ".get_board_title($_GET['tableID'], $article_id);
	}
} 
else { 
	$DOCUMENT_TITLE = "Jaram :: ".$DOCUMENT_SUBMENU['main_menu'];
}

// 한줄 메시지 처리
if (is_login()) {
	// 메시지를 받아서 변수에 저장
	$dbo = ZendDB::getDBO();
	$groups = implode(",", get_gids());

	// 아직 한번도 읽지 않은것
	$result = $dbo->fetchAll("SELECT a.msg_type,a.msg_id, a.msg_text, b.read_count, b.status, c.uid, c.user_name, c.user_id FROM jaram_one_line AS a LEFT JOIN jaram_one_line_user AS b ON a.msg_id=b.msg_id LEFT JOIN jaram_users AS c ON a.uid=c.uid WHERE (a.target_pid=0 OR a.target_pid=:pid) AND a.target_gid IN ({$groups}) AND b.status IS NULL AND NOW() BETWEEN start_date AND end_date", array('pid'=>$PROGRAM_INFO['program_id']));

	if (!$result) {
        // 한번 읽었지만, 취소하지 않은 것들
		$result = $dbo->fetchAll("SELECT a.msg_type,b.read_count,a.msg_id, a.msg_text, b.status, c.uid, c.user_name, c.user_id  FROM jaram_one_line AS a LEFT JOIN jaram_one_line_user AS b ON a.msg_id=b.msg_id  LEFT JOIN jaram_users AS c ON a.uid=c.uid WHERE (a.target_pid=0 OR a.target_pid=:pid) AND a.target_gid IN ({$groups}) AND (b.status = 'read')  AND NOW() BETWEEN start_date AND end_date", array('pid'=>$PROGRAM_INFO['program_id']));
	}

	$selected_key = array_rand($result);
	$_JARAM_INFO_MSG = $result[$selected_key];
}
$trash = ob_get_contents();
ob_end_clean();

// 헤더
$import_css = WEB_ABS_PATH."/css/css2.css";
if (eregi("MSIE.6", $_SERVER['HTTP_USER_AGENT'])) {
    $import_css .= ",".WEB_ABS_PATH."/css/ie6.css";
}

$header_tpl = new JaramSmarty();
$header_tpl->assign('web_abs_path', WEB_ABS_PATH);
$header_tpl->assign('document_title', $DOCUMENT_TITLE);
$header_tpl->assign('jaram_sub_menu', $DOCUMENT_SUBMENU['menu']);
$header_tpl->assign('main_menu', $DOCUMENT_SUBMENU['main_menu']);
$header_tpl->assign('sub_menu', $DOCUMENT_SUBMENU['sub_menu']);
$header_tpl->assign('layout_style', '');
$header_tpl->assign('import_css', $import_css);
$header_tpl->assign('import_js', $import_js);
$header_tpl->assign('inline_javascript', $inline_javascript);
$header_tpl->display('common/header.tpl');

//$logger->debug("trash size : ".strlen($trash));

if ($_JARAM_INFO_MSG) {
    $msg_func = "jaram_".$_JARAM_INFO_MSG['msg_type'];
    $_JARAM_INFO_MSG['msg_text'] = "{$_JARAM_INFO_MSG['msg_text']} <small>by</small> <a href=\"".WEB_ABS_PATH."/jaram/memberinfo/?gid={$_JARAM_INFO_MSG['uid']}\">{$_JARAM_INFO_MSG['user_name']}</a>";

	if (empty($_JARAM_INFO_MSG['status'])) {
		$set = array('msg_id'=>$_JARAM_INFO_MSG['msg_id'], 'uid'=> $_SESSION['jaram_user_uid'], 'status' => 'read', 'update_date'=>date("c"));
		$dbo->insert("jaram_one_line_user", $set);
		echo $msg_func($_JARAM_INFO_MSG['msg_text'],$_JARAM_INFO_MSG['msg_id'], false);
		
		$run_effect = "<script type=\"text/javascript\"><!--
		Try.these(function() {Effect.Appear('jaram_one_line_msg')});
		--></script>";
		echo $run_effect;
	} else {
		$dbo->query("UPDATE jaram_one_line_user SET read_count = read_count+1 WHERE msg_id=:msg_id AND uid=:uid", array('msg_id'=>$_JARAM_INFO_MSG['msg_id'], 'uid'=>$_SESSION['jaram_user_uid']));

		echo $msg_func($_JARAM_INFO_MSG['msg_text'],$_JARAM_INFO_MSG['msg_id'], true);

		// 일정횟수가 넘게 읽혔으나 close처리가 되지 않은 것들을 close처리
		// garbage collect 역할을 하는 객체에 넣고 실행하도록
		$dbo->query("UPDATE jaram_one_line_user SET status=:status WHERE msg_id=:msg_id AND uid=:uid AND read_count >= :max_read_count", array('status'=>'close', 'msg_id'=>$_JARAM_INFO_MSG['msg_id'], 'uid'=>$_SESSION['jaram_user_uid'], 'max_read_count'=>$CONFIG->one_line_read_limit));
	}
}
