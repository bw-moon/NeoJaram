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
shmop_delete($shm_id);
?>
<script>alert('소거 완료');</script>