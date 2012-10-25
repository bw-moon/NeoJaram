<?php
login_check();

$dbo = ZendDB::getDBO();
$query = "SELECT 
            a.gid, g.group_name, g.group_description
        FROM jaram_auth_access AS a 
            LEFT JOIN jaram_groups AS g ON(a.gid = g.gid) 
            LEFT JOIN jaram_programs AS p ON(a.pid = p.pid AND a.bid=p.bid) GROUP BY a.gid";
$result = $dbo->fetchAll($query);


$smarty = new JaramSmarty();
$smarty->assign('groups', $result);
$smarty->display('front/auth_list.tpl');
?>
