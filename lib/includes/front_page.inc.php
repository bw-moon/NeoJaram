<?php

if (is_login()) {
	include_once "personal_toolbar.inc.php";
}

$NOTICE = recent_article_list("notice", 2);
$NEWS_AND_TIPS = recent_article_list("newstips", 2);
$QNA = recent_article_list("qna", 2);
$dbo = ZendDB::getDBO();

dbconn();

if (isDev()) {
	$S_query = "
		SELECT a.seminar_id, a.seminar_topic, a.seminar_desc, a.seminar_group_id, c.group_name, b.schedule_start 
		FROM
			jaram_seminar AS a 
			LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id 
			LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
		ORDER BY b.schedule_start DESC LIMIT 1";
} else { 
	$S_query = "
		SELECT a.seminar_id, a.seminar_topic, a.seminar_desc, a.seminar_group_id, c.group_name, b.schedule_start 
		FROM
			jaram_seminar AS a 
			LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id 
			LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
		WHERE b.schedule_start btween ".date("Ymd",time()+(3600*24*6))." AND ".date("Ymd", time())." 
		ORDER BY b.schedule_start ASC LIMIT 1";
}

//$logger->debug($S_query);
$SEMINAR = $dbo->fetchRow($S_query);

if (!empty($SEMINAR)) {
	$SEMINAR_INFO = "<h3><img src=\"./images/icons/page_white_text.gif\"/> Weekly Seminar</h3>";
	$SEMINAR_d = get_each_date($SEMINAR['schedule_start']);
	$SEMINAR_INFO .= "<strong>Topic:</strong> <a href=\"".WEB_ABS_PATH."/studyzone/seminar/seminar_view.php?seminar_id=".$SEMINAR['seminar_id']."\">".$SEMINAR['seminar_topic']."</a> ";
	$SEMINAR_INFO .= "by <a href=\"/jaram/memberinfo/?gid=".$SEMINAR['seminar_group_id']."\">".$SEMINAR['group_name']."</a><br/>";
	$SEMINAR_INFO .= "<strong>Date:</strong> <span class=\"sub\">".$SEMINAR_d['year']."/".$SEMINAR_d['month']."/".$SEMINAR_d['day']."</span><br/>";
	$SEMINAR_INFO .="<p>";
	$SEMINAR_INFO .= nl2br($SEMINAR['seminar_desc']);	
	$SEMINAR_INFO .="</p>";

}

$BOOK = $dbo->fetchRow("SELECT id,title,note,date,extend1,extend2 FROM jaram_board WHERE bid='books' ORDER BY id DESC LIMIT 1");
if (!empty($BOOK)) {
	$BOOK_E1= explode("|", $BOOK['extend1']);
	$BOOK_E2= explode("|", $BOOK['extend2']);

	$twoIsbn=substr($BOOK_E2[2],0,2);

	$BOOK_INFO = "<h3><img src=\"./images/icons/book.gif\"/> Recent Book Review</h3>";

	if ($twoIsbn == 89) {
		//$BOOK_INFO .= "<a href=\"http://www.aladdin.co.kr/catalog/book.asp?ISBN=".$BOOK_E2[2]."\" target=\"_blank\"><img border=\"1\" src=\"http://image.aladdin.co.kr/cover/cover/".$BOOK_E2[2]."_1.jpg\" align=\"left\" style=\"margin-right:10px\" alt=\"".$BOOK['title']."\ title=\"".$BOOK['title']."\"/></a>";
	}
    else {
		//$BOOK_INFO .= "<a href=\"http://www.amazon.com/exec/obidos/ISBN=".$BOOK_E2[2]."\" target=\"_blank\"><img border=\"1\" src=\"http://images.amazon.com/images/P/".$BOOK_E2[2].".01.MZZZZZZZ.gif\" align=\"left\" alt=\"".$BOOK['title']."\"/></a>";
	}

	$BOOK_INFO .= "<a href=\"".WEB_ABS_PATH."/board/view.php?tableID=books&amp;id=".$BOOK['id']."\" class=\"a12b\"><strong>".$BOOK['title']."</strong></a> by ".$BOOK_E1[0]."<br/>";
	$BOOK_INFO .= "<strong>Publisher:</strong> ".$BOOK_E2[0]."<p>".nl2br($BOOK['note'])."</p>";
}



require_once (LIBRARY_PATH.'/magpierss-0.5.2/rss_fetch.inc');

$url = 'http://192.168.1.91/wiki/wiki.php/RecentChanges?action=rss_rc';
$rss = fetch_rss($url);


$front_page_tpl = new JaramSmarty();
$front_page_tpl->assign('notice', $NOTICE);
$front_page_tpl->assign('book_info', $BOOK_INFO);
$front_page_tpl->assign('seminar_info', $SEMINAR_INFO);
$front_page_tpl->assign('qna', show_article_list($QNA, "qna"));
$front_page_tpl->assign('news', show_article_list($NEWS_AND_TIPS, "newstips"));
$front_page_tpl->assign('rss', $rss);

if (isset($_GET['error'])) 
{
    $front_page_tpl->assign('msg_type', 'error');
    $front_page_tpl->assign('msg', urldecode($_GET['error']));
}
else if (isset($_GET['message'])) 
{
    $front_page_tpl->assign('msg_type', 'information');
    $front_page_tpl->assign('msg', urldecode($_GET['message']));
}

$front_page_tpl->display('front/front_page.tpl');
?>