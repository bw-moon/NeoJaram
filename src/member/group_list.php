<?php
login_check();
$group_obj = new JaramGroup();

// 개인 그룹을 제외한 그룹을 보여줌
$group_row = $group_obj->getCommonGroups($_SESSION['jaram_user_uid']);

// 가입 대기중인 그룹
$result_wait = $group_obj->getJoinWaitGroup($_SESSION['jaram_user_uid']);

// 승인을 대기 목록
$result_accept_wait = $group_obj->getAcceptWaitList($_SESSION['jaram_user_uid']);

$group_list_tpl = new JaramSmarty();
$group_list_tpl->assign('group_total', $group_row);
$group_list_tpl->assign('group_join_wait', $result_wait);
$group_list_tpl->assign('group_accept_wait', $result_accept_wait);
$group_list_tpl->display('front/group_list.tpl');