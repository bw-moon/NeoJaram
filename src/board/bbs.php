<?php
// 게시판 리스트 프로그램
require_once "include/setup.php";
require_once "include/info.php";
require_once "include/check.php";

if (!chk_auth("auth_view")) {

}

// 스마티
if (!$include_mode) {
    $import_js = "{$web_abs_path}/js/navigator.js";
    // 캐시해서는 안될 유동적인 페이지 일 경우
    require_once (INCLUDE_PATH.$d."header.inc.php");
}

$comment_template = new JaramSmarty(); 
$engine = new JaramBoard($_REQUEST['tableID']);

$parsed_comments = array();

$comments = $engine->getRecentComments();
if ($comments) {
    $comment_template->assign("title", "이곳에 새롭게 등록된 코멘트");
} else {
    $comment_template->assign("title", "다른 곳에 새롭게 등록된 코멘트(이 게시판엔 새로운 코멘트가 없습니다)");
    $comments = $engine->getTotalRecentComments();
}
$comment_template->assign("comments",$comments);
$comment_template->display("board/{$_TEMPLATE}/recent_comments.tpl");

// 기본변수 셋업
$smarty = new JaramSmarty(); 
$tableID=$_REQUEST['tableID'];
$pageID = ($_REQUEST['pageID']) ? $_REQUEST['pageID']  : 1;


//페이저
require_once 'Pager.php';
require_once 'Pager/Sliding.php';
$params = array(
	'mode'       => 'Sliding',
	'perPage'    => $VIEW_SCALE,
	'delta'      => 3,
	'totalItems'   => $engine->getTotalCount($_REQUEST)
);
$pager = & Pager::factory($params);
$links = $pager->getLinks();
$fetch_limit = $pager->getOffsetByPageId();


if ($USING_CATEGORY=="true")
{
	$smarty->assign("categoryData", $engine->getCategoryList($_REQUEST['tableID']));
}


//정렬
$smarty->assign('asc', $_REQUEST['asc'] ? 0 : 1);

// 검색과 카테고리 데이터
$smarty->assign('search_types', array( 
		'note' => '본문', 
		'title' => '제목', 
		'name' => '이름',	
		'comment' => '코멘트'
   )); 
$smarty->assign("search_value",$_REQUEST['search_value']);
$smarty->assign("search_type",$_REQUEST['search_type'] ? $_REQUEST['search_type'] : array('note', 'title'));
$smarty->assign("category",$_REQUEST['category']);


// 작성중이었던 글을 뽑음
$smarty->assign('draftArticles', $engine->getDraftArticles());

$smarty->assign("tableID",$tableID);
$smarty->assign("listData", $engine->getArticleList($_REQUEST, $fetch_limit[0], $params['perPage']));
$smarty->assign("articleStartNum", ($pager->numItems() -1)- ($VIEW_SCALE * ($pager->getCurrentPageID() -1)));

// 페이지
$smarty->assign("pageID", $pageID);
$smarty->assign("nowPage",$pageID);
$smarty->assign("preViewStart",$preViewStart);
$smarty->assign("pagePrintArray",$pagePrintArray);
$smarty->assign("nextViewStart",$nextViewStart);
$smarty->assign("pager_links", $links);

// 정렬
$smarty->assign("nowSortData",$_REQUEST[sortData]);
$smarty->assign("printValueSortNo",$printValueSortNo);
$smarty->assign("printValueSortCount",$printValueSortCount);

// 링크
$smarty->assign("homepage_path",$homepage_path);
$smarty->assign("_template", $_TEMPLATE);

// 파싱시간
$microtimeE = explode (" ", microtime());
$parsingtime = ($microtimeE[0]-$microtimeS[0])+($microtimeE[1]-$microtimeS[1]);

// 출력
$smarty->display("board/{$_TEMPLATE}/list.tpl");


if (!$include_mode) {
	// prev, next 스크립트
    parse_str($_SERVER['QUERY_STRING'], $url_params);
    if ($url_params['pageID'] > 1) {
        $url_params['pageID']--;
        $prev_link = "./bbs.php?".http_build_query($url_params, '', '&');
        $url_params['pageID']++;
        if ($url_params['pageID'] < $pager->numPages()) {
            $url_params['pageID']++;
            $next_link = "./bbs.php?".http_build_query($url_params, '', '&');
        }
    } else {
        $url_params['pageID'] = 2;
        $next_link = "./bbs.php?".http_build_query($url_params, '', '&');
    }
    echo "
	<script type=\"text/javascript\">
    <!--
    previous_link = '{$prev_link}';
    next_link = '{$next_link}';
    -->
    </script>
    ";

    include_once  (INCLUDE_PATH.$d."footer.inc.php");
}