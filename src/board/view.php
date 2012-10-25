<?php
//게시판 내용 보기 프로그램
require_once "include/setup.php";
require_once "include/info.php";

include("../editor/fckeditor/fckeditor.php") ;

$pageID = $_REQUEST['pageID'];

$include_mode = true;

$import_js = "{$web_abs_path}/js/board.js,{$web_abs_path}/editor/fckeditor/fckeditor.js";

// 디스플레이..부분
// 해더 파일
require_once INCLUDE_PATH."/header.inc.php";

// _GET, _POST
$tableID=$_REQUEST['tableID'];

// 파싱 타임
$microtimeS = explode (" ", microtime());

// 스마티
$smarty = new JaramSmarty(); 
$board = new JaramBoard($tableID);

// 사용자 정의 함수/필터 등록
require_once "include/user_function.php";


// 잘못된 게시물 ID값이 없을 경우
if (!$_REQUEST[id])
{
	$smarty->display("error.tpl");
	exit;
}
else  {
	$id=$_REQUEST[id];
}

$dbo = ZendDB::getDBO();

require_once "include/check.php";


// 페이지를 계산해서 구하도록 해야 함 - 070221 병원
if ($_REQUEST['pageID'])
{
	$pageID = $_REQUEST['pageID'];
}
else 
{
	$total = $dbo->fetchOne("SELECT COUNT(*) FROM {$M_TABLE} WHERE bid=:bid", array('bid'=>$tableID));
	$pageID = (int)(($total-$viewData['locate']) / $VIEW_SCALE)+1;
}

$viewStart = ($pageID - 1 ) * $VIEW_SCALE;


$viewData  = $board->getArticle($_REQUEST);
$viewData['note'] = stripslashes($viewData['note']);
$viewData['title'] = stripslashes($viewData['title']);

if ($USING_FILE=="true")
{
	if ($viewData['filesize']<1024)
		$viewData['filesize'] .= "B";
	elseif ($viewData['filesize']<1048576)
		$viewData['filesize'] = (int)($viewData['filesize']/1024)."KB";
	else
		$viewData['filesize'] = (int)($viewData['filesize']/1048576)."MB";
}

if ($USING_COMMENT=="true")
{
	$smarty->assign("commentData",$board->getComments($id));
}

if ($USING_FILE=="true")
{
	$files = $dbo->fetchAll("select * from {$M_TABLE_FILE} where sub_id=:id", array('id'=>$id));
	$smarty->assign("fileData",$files);
}

$boardData = $dbo->fetchAll("select name,title from {$M_ADMIN_TABLE} where `group`=:gid AND `name` != :name", array('gid'=>$GROUP_ID, 'name'=>$tableID));    

$smarty->assign("boardData",$boardData);

$oFCKeditor = new FCKeditor('s_note');
$oFCKeditor->BasePath	= "{$web_abs_path}/editor/fckeditor/";
$oFCKeditor->Height       = '200px';
$oFCKeditor->ToolbarSet  = 'Basic';

$smarty->assign("oEditor", $oFCKeditor->CreateHtml());
$smarty->assign("tableID",$tableID);
$smarty->assign("id",$id);
$smarty->assign("viewData",$viewData);
$smarty->assign("pageID",$pageID);
$smarty->assign("sortData",$_REQUEST['sortData']);
$smarty->assign("imgData",$imgData);
$smarty->assign("loginCheck",$SESSION_USER_ID);
$smarty->assign("_template", $_TEMPLATE);
$smarty->assign("imageManager", new JaramImage());

// 전체 파싱 시간
$microtimeE = explode (" ", microtime());
$parsingtime = ($microtimeE[0]-$microtimeS[0])+($microtimeE[1]-$microtimeS[1]);

$smarty->display("board/{$_TEMPLATE}/view.tpl");


// 아래 내용 보여줄려면 캐시를 쓰는게 제일 좋을 듯, 다른 방법은 느림.
//require_once 'bbs.php';

require_once INCLUDE_PATH."/footer.inc.php";
?>