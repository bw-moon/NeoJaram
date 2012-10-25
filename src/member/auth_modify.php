<?php
login_check();

$query = "SELECT 
            a.gid, p.main_menu, p.sub_menu, a.auth_view,
            a.auth_read, a.auth_post, a.auth_comment, a.auth_edit, a.auth_delete, a.auth_announce,
            a.auth_vote, a.auth_upload, a.pid, a.bid
        FROM jaram_auth_access AS a 
            LEFT JOIN jaram_programs AS p ON(a.pid = p.pid AND a.bid=p.bid)
        WHERE a.gid = :gid";

$group_info = get_group_info($_GET['gid']);
$result = ZendDB::getDBO()->fetchAll($query, array('gid'=>$_GET['gid']));

$smarty = new JaramSmarty();
$smarty->assign('group_info', $group_info);
$smarty->assign('auth_list', $result);
$smarty->display('front/auth_detail.tpl');
?>




