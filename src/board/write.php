<?php
require_once "include/setup.php";
require_once "include/info.php";
include_once ("../editor/fckeditor/fckeditor.php") ;

//$import_js = "{$web_abs_path}/editor/tinymce/jscripts/tiny_mce/tiny_mce_src.js,{$web_abs_path}/js/tiny_editor.js";
$import_js = "{$web_abs_path}/js/board.js,{$web_abs_path}/editor/fckeditor/fckeditor.js";

// 글 수정의 경우는 자동 저장 없음
if (!$_REQUEST['modID']) {
	$inline_javascript = "<script type=\"text/javascript\">
	new PeriodicalExecuter(doAutoSaveArticle, 5);
	</script>";
}


// 해더 파일
require_once INCLUDE_PATH."/header.inc.php";


// 글 쓰기시 자동 로그인 추가
// 2006.10.09 by zeru
require_once(INCLUDE_PATH."/auto_login.inc.php"); 

$smarty = new JaramSmarty();
$smarty->caching=false;

// 사용자 정의 함수/필터 등록
require_once "include/user_function.php";


// 이지윅 에디터 세팅
$oFCKeditor = new FCKeditor('note') ;
$oFCKeditor->BasePath	= "{$web_abs_path}/editor/fckeditor/";
$oFCKeditor->Height       = '400px';
$oFCKeditor->Value = '';
$oFCKeditor->ToolbarSet = 'Basic';

// DB
$dbo = ZendDB::getDBO();

// _GET, _POST
if ($HTTP_GET_VARS[tableID])
	$tableID=$HTTP_GET_VARS[tableID];

require_once "include/check.php";


if ($USING_CATEGORY=="true")
{
	$category = $dbo->fetchAll("SELECT * FROM jaram_board_category WHERE bid=:bid ORDER BY id ", array('bid'=>$tableID));
	$smarty->assign("categoryData",$categoryData);
}

$smarty->assign("totalFileSize", "0Byte");
$smarty->assign("pageTitle", "Post Article");

// 답글 쓸때 Title, 내용을 넘겨줌
if ($HTTP_GET_VARS[replyID] && $USING_REPLY=="true")
{
	$article = $dbo->fetchRow("select title,note from $M_TABLE where id=:id", array('id'=>$_GET['replyID']));
    $article['note'] = "<br/><br/>-------------------- 원문 -------------------<br/><blockquote>".$article['note']."</blockquote>";
    $smarty->assign("USING_REPLY","true");
	$smarty->assign("replyID",$_GET['replyID']);
}
// 글 수정시...
else if ($_REQUEST['modID'])
{
    chk_auth('modify');

    $article = $dbo->fetchRow("select * from {$M_TABLE} where id=:id", array('id'=>$_GET['modID']));	
	$fileData = $dbo->fetchAll("SELECT * FROM {$M_TABLE_FILE} WHERE sub_id=:sub_id", array('sub_id'=>$_GET['modID']));
	$fileOptions = array();

    foreach ($fileData as $file) {
		$fileOptions[$file['file_id']."-".$file['file_size']] = sprintf("(%s) %s", getFancyFileSize($file['file_size']),$file['file_name']);
	}

    $smarty->assign("MOD_MODE","true");
	$smarty->assign("modID",$_GET['modID']);
	$smarty->assign("fileOptions", $fileOptions);
	$smarty->assign("pageTitle", "Modify Article");
} else {
    $article = array();
}

// draft_id가 있으면 그 글의 내용을 넣어줌.
if ($_GET['draft_id']) {
    $draft_id = $_GET['draft_id'];
    $board = new JaramBoard($tableID);
    $draft = $board->getDraftArticle($draft_id);
    $article['note'] = $draft['note'];
    $article['title'] = $draft['title'];
}

$smarty->assign('draft_id', $draft_id);

$smarty->assign("dataValue",$article);
$smarty->assign("tableID",$tableID);
$smarty->assign("loginCheck",$_SESSION['jaram_user_uid']);

// 광고 스팸 차단용
$smarty->assign("mtime",getmicrotime());

// 썸네일용
$smarty->assign("USING_PREVIEW_IMG",$USING_PREVIEW_IMG);
$smarty->assign("PREVIEW_IMG_X",$PREVIEW_IMG_X);
$smarty->assign("PREVIEW_IMG_Y",$PREVIEW_IMG_Y);

//$smarty->cache_lifetime=0;//3600*24;

// 이지윅 에디터 연결
$oFCKeditor->Value = $article['note'];
$smarty->assign("oEditor", $oFCKeditor->CreateHtml());

$smarty->display("board/{$_TEMPLATE}/write.tpl");

// 다리 파일 ㅡㅡ;;
require_once INCLUDE_PATH."/footer.inc.php";

?>