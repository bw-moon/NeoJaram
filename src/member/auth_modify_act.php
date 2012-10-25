<?
login_check();
dbconn();

$chk = 0;
if ($_POST['act'] == "Delete") {
    $query = "DELETE FROM jaram_auth_access ";
}
else if ($_POST['act'] == "Modify") {
    // 배열 초기화
    $auth = array('view' => 0,
                        'read' => 0,
                        'post' => 0,
                        'comment' => 0,
                        'edit' => 0,
                        'delete' => 0,
                        'announce' => 0,
                        'vote' => 0,
                        'upload' => 0);

    $query = "UPDATE jaram_auth_access SET "; 

    $count = 0;
    foreach ($auth as $key => $value) {
        $count++;
        if ($_POST['perm'][$key] == 1) {
            $auth[$key] = 1;
        } else {
            $auth[$key] = 0;
        }
        
        $query .= "auth_".$key."=".$auth[$key];
        if (count($auth) != $count) {
            $query .= ", ";
        }
        
    }
}
else {
}
//show($_POST);
//show($query);
//$result = 


$query .= " WHERE gid='".$_POST["gid"]."' AND pid='".$_POST["pid"]."'";
if(!empty($_POST['bid'])) {
    $query .= "AND bid='".$_POST["bid"]."';";
}


if ($_GET['mode'] == "delete_all") {
    $chk = 1;
    if ($_GET['answer'] == "yes") {
        $query = "DELETE FROM jaram_auth_access WHERE gid = ".$_GET['gid'];
        $chk = 0;
    } 
    else if ($_GET['answer'] == "no") {
        p_redirect("/?page=auth_modify&amp;gid=".$_POST['gid']);
    } else {
        show_confirm_msg("정말 모든 권한을 삭제합니까?");
    }
    
}

if ($chk == 0) {
    if (@mysql_query($query)) {
        if ($_GET['mode'] == "delete_all") {
            p_redirect("/?page=auth_management");
        } else {
            p_redirect("/?page=auth_modify&amp;gid=".$_POST['gid']);
        }
    } else {
        show_error_msg("작업 도중에 문제가 발생하였습니다.<br/>".$query);
    }
}
?>