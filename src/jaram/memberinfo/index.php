<?php
include_once realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php";

$smarty = new JaramSmarty();
$group = new JaramGroup($_GET['gid']);
$result = $group->getGroupMember();

// 결과가 없을 때
if (!$result) {
	print "존재하지 않는 그룹이거나 회원입니다";
	include_once INCLUDE_PATH."/footer.inc.php";
	exit;
} 
else if (count($result) == 1) {
	$smarty->assign('member_info', $result[0]);
	$smarty->assign('user_groups', $group->getGroupList());
	$smarty->display("jaram/member_detail.tpl");
}
else {
	$line_link = array();
	foreach ($result as $rows) {
		if (!isset($line_link[$rows['user_number']])) {
			$line_link[$rows['user_number']] = sprintf("<a href=\"#%s\">%s기</a>", $rows['user_number'], $rows['user_number']);
		}
	}
	$smarty->assign('line_link', implode(" | ", $line_link));
	$smarty->assign('group_list', $result);
	$smarty->display("jaram/member_list.tpl");

}

// 푸터 파일 인클루드
include_once INCLUDE_PATH."/footer.inc.php";
?>