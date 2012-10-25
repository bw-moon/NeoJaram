<?php
dbconn();

$dbo = ZendDB::getDBO();
$where = $dbo->quoteInto('uid=?', $_GET['uid']);
$where .= ' AND '.$dbo->quoteInto('gid=?', $_GET['gid']);

$group_obj = new JaramGroup();


if (!(is_admin() || $group_obj->isOwner($_GET['gid'], $_SESSION['jaram_user_uid']))) {
    show_error_msg("그룹 관리자만 사용할 수 있습니다.", "std");
}
else if ($_GET['mode'] == "accept") {
    if ($_GET['answer'] == "yes") {    
        $group_obj->acceptJoinGroup($_GET['gid'], $_GET['uid']);
        show_std_msg("그룹에 정상적으로 등록되었습니다.", "std", "./?page=group_permit");
    }
    else if ($_GET['answer'] == "no") {
        $group_obj->cancelJoinGroup($_GET['gid'], $_GET['uid']);
        show_std_msg("정상적으로 신청이 취소되었습니다.", "std", "./?page=group_permit");
    }
    else {
        $user_info = $group_obj->getInfo($_GET['uid']);
        $group_info = $group_obj->getInfo($_GET['gid']);
        $msg = "<strong>".$user_info['group_name']."</strong> 회원을 <strong>".$group_info['group_name']."</strong> 그룹의 멤버로 등록시키려고 합니다.<br/>\n승인하시겠습니까?\n<strong>아니오</strong> 버튼을 누르시면 그룹 가입신청 내역이 삭제됩니다."; 
        show_confirm_msg($msg);
    }
}

else if ($_GET['mode'] == "withdraw") {
    if ($_GET['answer'] == "yes") {
        $sql = "DELETE FROM jaram_user_group WHERE uid='".$_GET['uid']."' AND gid='".$_GET['gid']."';";
        if (mysql_query($sql)) {
            show_std_msg("정상적으로 그룹에서 삭제되었습니다.", "std", "./?page=group_permit");
        } else {
            show_error_msg("삭제과정에서 문제가 발생하였습니다. 아래 쿼리를 복사하여 메일로 보내주세요.\n".$sql, "std");
        }
    } 
    else if ($_GET['answer'] == "no") {
        p_redirect("./?page=group_permit");
    }
    else {
        echo "<br/>";
        $user_info = get_group_info($_GET['uid']);
        $group_info = get_group_info($_GET['gid']);
//		show($user_info);
        $msg = "추가작업 : <a href=\"./?page=group_permit&amp;mode=append_admin&amp;uid=".$_GET['uid']."&amp;gid=".$_GET['gid']."\">관리자로 추가</a>";
        $msg .= " | <a href=\"./?page=group_permit&amp;mode=remove_admin&amp;uid=".$_GET['uid']."&amp;gid=".$_GET['gid']."\">관리 권한 삭제</a>\n\n";
        $msg .= "<b>".$user_info['group_name']."</b> 회원을 <b>".$group_info['group_name']."</b> 그룹의 멤버에서 강퇴시키려고 합니다.\n한번 강퇴된 회원은 다시 가입 신청을 하고 승인이 있어야 그룹을 이용할 수 있습니다.\n\n강퇴하시겠습니까?"; 
        show_confirm_msg($msg);
    }
}

else if ($_GET['mode'] == "del_group") {
    if ($_GET['gid'] < 3000 && 1000 < $_GET['gid']) {
        show_error_msg("gid 1000~3000번 사이의 그룹은 관리자만 관리할 수 있습니다.", "std");
    }
    else if ($_GET['answer'] == "yes") {
        $sql = "DELETE FROM jaram_user_group WHERE gid='".$_GET['gid']."';";
        $sql2 = "DELETE FROM jaram_groups WHERE gid='".$_GET['gid']."';";
        $sql3 = "UPDATE jaram_group_pool SET flag = 'x' WHERE gid='".$_GET['gid']."';";

        if (mysql_query($sql) && mysql_query($sql2) && mysql_query($sql3)) {
            show_std_msg("정상적으로 그룹에서 삭제되었습니다.", "std", "./?page=group_permit");
        } else {
            show_error_msg("삭제과정에서 문제가 발생하였습니다. 아래 쿼리를 복사하여 메일로 보내주세요.\n".$sql."\n".$sql2."\n".$sql3, "std");
        }
    } 
    else if ($_GET['answer'] == "no") {
        p_redirect("./?page=group_permit");
    }
    else {
        echo "<br/>";
        $group_info = get_group_info($_GET['gid']);
        $msg = "<b>".$group_info['group_name']."</b> 그룹을 삭제하고자 합니다.\n한번 삭제된 그룹은 다시 복구 할 수 없으니 신중하게 선택해주시길 바랍니다."; 
        show_confirm_msg($msg);
    }
}

else if ($_GET['mode'] == "append_admin") {
    $sql = "UPDATE jaram_user_group SET status='o' WHERE uid='".$_GET['uid']."' AND gid='".$_GET['gid']."';";
    mysql_query($sql);
    show_std_msg("정상적으로 관리자로 추가되었습니다.", "std", "./?page=group_permit");
}

else if ($_GET['mode'] == "remove_admin") {
    $sql = "UPDATE jaram_user_group SET status='' WHERE uid='".$_GET['uid']."' AND gid='".$_GET['gid']."';";
    mysql_query($sql);
    show_std_msg("정상적으로 관리자 권한을 제거하였습니다.", "std", "./?page=group_permit");
}
