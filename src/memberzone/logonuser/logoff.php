<?
/***********************************************************
 * filename : logoff.php
 * author : seazures
 * last update : 2004. 1. 10. sat
 *
 ***********************************************************/
// 공유메모리 접근
$shm_id = shmop_open(0xfff,"w", 0777, 1500);
// Get shared memory block's size
$shm_size = shmop_size($shm_id);

// 공유메모리 읽기
$user_list = shmop_read($shm_id, 0, $shm_size);
if(!$user_list) {
   echo "Couldn't read from shared memory block\n";
}

// 현재 접속중인 사람의 정보를 지운다.
strtr($user_list, $_SESSION['jaram_user_name'], "");
$shm_bytes_written = shmop_write($shm_id, $user_list, 0);
shmop_close($shm_id);
?>
<script>alert('계정정보를 지웠다뇨!');</script>