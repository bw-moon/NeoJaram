<?php
// 글 내용 DB에 입력 프로그램
set_time_limit(0);
require_once "include/setup.php";
require_once "include/info.php";

require_once(INCLUDE_PATH."/auto_login.inc.php"); // include_once로 바꿈 by zeru at 2006.10.09

// jaram 게시판 모니터링 관련
$smarty = new JaramSmarty(); 

// 임시 공지 사항 방어

// _GET, _POST
$tableID=$_REQUEST['tableID'];

require_once "include/check.php";

if($tableID=="notice" && !$SESSION_USER_ID)
{
	echo(" <script>
			  window.alert('공지사항은 회원만 쓸 수 있습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

// 스팸 클라이언트 확인
if(is_spam_client())
{
	echo(" <script>
			  window.alert('스팸 IP로 등록되었습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

if (!$_POST["passwd"] && !$SESSION_USER_ID)
{
	echo(" <script>
			  window.alert('암호를 입력하지 않았습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}
// 로그인 경우를 생각
if (!$HTTP_POST_VARS["name"] && !$SESSION_USER_ID)
{
	echo(" <script>
			  window.alert('이름을 입력하지 않았습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}
if (!$HTTP_POST_VARS["title"])
{
	echo(" <script>
			  window.alert('제목을 입력하지 않았습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}
if (!$HTTP_POST_VARS["note"])
{
	echo(" <script>
			  window.alert('내용을 입력하지 않았습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

if(!is_login())
{
	$isHan=false;
	$noteData=$_POST['note'];
	for($i = 0 ; $i < strlen($noteData);$i++)
	{
		if((int)$noteData{$i}>127)
		{
			$isHan=true;
			break;
		}
	}
	if(!$isHan)
	{
	echo(" <script>
			  window.alert('한글 문자가 전혀 없습니다. 광고글로 추측합니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
	}
}

if($_SERVER['HTTP_REFERER'] == "")
{
	exit;
}

if(preg_match("/upload_result.php/i",$_SERVER['REDIRECT_URL']))
{
	echo(" <script>
			  window.alert('정상적인 경로로 오지 않았습니다. 광고글로 추측합니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

$dbo = ZendDB::getDBO();
$board = new JaramBoard($_POST['tableID']);
try {
    if ($_POST['modID'] > 0) {
        $board->updateArticle($_POST);
    }
    else if ($_POST['replyID'] > 0) {
        $board->replyArticle($_POST);
    }
    else {
        $board->postArticle($_POST);
    }
} catch (Exception $e) {
	$GLOBALS['logger']->err($e->getMessage());
}

// 리스트 캐시 삭제
//$smarty->clear_cache(null,$tableID."|list");
//$smarty->clear_cache(null,$tableID."|view|$nextID");
// 페이지 이동
p_redirect("./bbs.php?tableID={$tableID}");