<?php
// 내 그룹 표시
$groups = get_groups_from_gid($_SESSION['jaram_user_uid']);
$show_groups = array();
foreach ($groups as $group)
{
	$show_groups[] = "<a href=\"./jaram/memberinfo/?gid={$group['gid']}\"><strong>{$group['group_name']}</strong><span class=\"sub\">({$group['gid']})</span></a>";
}

$dbo = ZendDB::getDBO();

// 모니터링 표시
$monitor_result = $dbo->fetchAll("SELECT b.id, p.main_menu, p.sub_menu, p.dir FROM jaram_programs AS p LEFT JOIN jaram_bbs_monitor AS b ON (p.pid = b.pid AND p.bid = b.bid) WHERE uid = :uid ORDER BY b.id ASC", array('uid'=>$_SESSION["jaram_user_uid"]));

if ($monitor_result) {
	$DOCUMENT_BBS_MONITOR = "<ul>";

	foreach ($monitor_result as $rows) {
		$DOCUMENT_BBS_MONITOR .= "<li><a href=\"".$rows['dir']."\">".$rows['sub_menu']."</a> <a href=\"./?page=monitor_del&amp;monitor_id=".$rows['id']."\" class=\"sub\">[Del]</a></li>\n";
	}
	$DOCUMENT_BBS_MONITOR .= "</ul>";
} else {
	$DOCUMENT_BBS_MONITOR = "<strong>모니터중인 BBS가 없습니다.</strong>";
}

// 북마크 표시

$bookmark_query = "SELECT * FROM jaram_bookmark WHERE uid = :uid ORDER BY id DESC;";
$bookmark_result = $dbo->fetchAll($bookmark_query, array('uid'=>$_SESSION['jaram_user_uid']));

if ($bookmark_result) {
	$count = 0;
	$DOCUMENT_BOOKMARKS .= "<ul>";
    foreach ($bookmark_result as $bookmark_rows)
	{
		$DOCUMENT_BOOKMARKS .= "<li><strong><a href=\"{$bookmark_rows['bookmark_url']}\">{$bookmark_rows['bookmark_title']}</a></strong> <a href=\"./?page=bookmark_del&amp;bookmark_id={$bookmark_rows['id']}\" class=\"sub\">[Del]</a>&nbsp;<a href=\"./?page=bookmark_edit&amp;bookmark_id={$bookmark_rows['id']}\" class=\"sub\">[Edit]</a></li>\n";
		$DOCUMENT_BOOKMARKS .= "</td></tr>\n";
		$count++;
	}
	$DOCUMENT_BOOKMARKS .= "</ul>";
} else {
	$DOCUMENT_BOOKMARKS .= "<strong>북마크된 페이지가 없습니다.</strong>";
}

$personal_page_tpl = new JaramSmarty();
$personal_page_tpl->assign('groups', implode(", ",$show_groups));
$personal_page_tpl->assign('bookmarks', $DOCUMENT_BOOKMARKS);
$personal_page_tpl->assign('monitors', $DOCUMENT_BBS_MONITOR);
$personal_page_tpl->display('front/personal_page.tpl');
?>