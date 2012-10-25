<?php

/*************************************
 *			admin_conf.php						*
 *			@decription : user config			*
 *			@author : serue						*
 *************************************/

	// 현재시간을 반환한다
    $C_time = time();

	$date = date('Ymd',$C_time);

	$signdate = $date;

	$user_ip = $REMOTE_ADDR;

?>