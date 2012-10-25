<?
login_check();
dbconn();

$return_state = 0;

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

// 쿼리 생성
foreach ($_POST['gid'] as $gid) {
    foreach ($_POST['program'] as $program) {
        foreach ($auth as $key => $value) {
            if ($_POST['perm'][$key] == 1 || $_POST['all_privilege'] == 1) {
                $auth[$key] = 1;
            } else {
                $auth[$key] = 0;
            }
        }
        list($pid, $bid)  = explode(":", $program);
        $sql  = "INSERT INTO jaram_auth_access VALUES($gid, $pid, '$bid', $auth[view], $auth[read], $auth[post], $auth[comment], $auth[edit], $auth[delete], $auth[announce], $auth[vote], $auth[upload]);";
        if (!mysql_query($sql)) {
            $return_state++;
        }
    }
}

if ($return_state == 0) {
    show_std_msg("정상적으로 권한이 조정되었습니다.", "back");
} else {
    show_error_msg("권한조정 중에 문제가 발생하였습니다.");
}
?>

