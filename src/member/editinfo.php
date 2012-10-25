<?php
login_check();

// 기본 값 가져오기
$user = new JaramUser($_SESSION['jaram_user_uid']);
$tag = new JaramTag();

$smarty = new JaramSmarty();
$smarty->assign('rows', $user->getUser());
$smarty->assign('user', $user);
$smarty->assign('msgr_type', $messenger_type);
$smarty->assign('skill_tags', $tag->getTagList('user_skill'));
$smarty->display('front/userinfo_form.tpl');
?>
