<?
/***********************************************************
 * filename : logonlist.php
 * author : seazures
 * last update : 2004. 1. 10. sat
 *
 ***********************************************************/
 // db include
include_once(realpath('../../lib/includes')."/library.inc.php");
dbconn();

// 공유메모리 접근
$shm_id = shmop_open(0xfff,"w", 0777, 1500);
if(!$shm_id) {
	shmop_open(0xfff,"c", 0777, 1500);
}

// Get shared memory block's size
$shm_size = shmop_size($shm_id);

// 공유메모리 읽기
$user_list = shmop_read($shm_id, 0, $shm_size);
if(!$user_list) {
   echo "Couldn't read from shared memory block\n";
}

// 현재 접속자 목록을 뿌려준다
?>
<table>
	<tr>
		<td> Login Users </td>
	</tr>
	<tr>
		<td> <?=$user_list?> </td>
	</tr>
</table>
<? shmop_close($shm_id);?>