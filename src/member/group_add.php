<?php
login_check();

$group_add_tpl = new JaramSmarty();
$group = new JaramGroup();

if ($_REQUEST['mode'] == "append") 
{
    $result = $group->insertGroup($_POST['group_name'], $_POST['group_desc'], $_SESSION['jaram_user_uid']);
    if ($result) {
        $group_add_tpl->assign('msg_type', 'info');
        $group_add_tpl->assign('msg', '그룹이 정상적으로 등록되었습니다.');
    } else {
        $group_add_tpl->assign('msg_type', 'warn');
        $group_add_tpl->assign('msg', '등록과정에서 문제가 발생하였습니다.');
    }
    $group_add_tpl->assign('mode', 'append');

} 
else if ($_REQUEST['mode'] == "modify") 
{

    if ($_REQUEST['group_name']) {
        $result = $group->updateGroup($_POST['gid'], $_POST['group_name'], $_POST['group_desc'], $_SESSION['jaram_user_uid']);
        $group_add_tpl->assign('msg_type', 'info');
        $group_add_tpl->assign('msg', '그룹이 정상적으로 수정되었습니다.');

    } 
    $group_add_tpl->assign('mode', 'modify');
    $group_add_tpl->assign('group', $group->getInfo($_REQUEST['gid']));

} 
else 
{
    $group_add_tpl->assign('mode', 'append');
}

$group_add_tpl->display('front/group_add_form.tpl');
