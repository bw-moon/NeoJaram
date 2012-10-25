<?php

/*************************************
 *			dbconn.php							*
 *			@decription : db connection		*
 *			@author : serue						*
 *************************************/

	include "./lib/conn_conf.php";

	$dbconn = mysql_connect($host,$user,$password) or die ("MySQL에 접속할 수 없습니다");
	mysql_select_db("neojaram") or die ("MySQL DB에 접속할 수 없습니다");

?>