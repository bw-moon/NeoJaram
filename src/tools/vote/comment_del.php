<?php
/*************************************
 *			comment_del.php					*
 *			@decription : comment delete	*
 *			@author : lovelyk2					*
 *************************************/
	include "./lib/function.php";			// function include
	include "./lib/admin_conf.php";	// admin config include

############## header ################
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");


// database connect
dbconn();

############## main ################


	$table="jaram_vote_comment";

	$user_uid = $_SESSION["jaram_user_uid"];

	$query="delete from " . $table . " where user_uid = " . $user_uid . " and comment_id = " . $_GET['id'];

	$result = @mysql_query($query);

	$affected_rows = @mysql_affected_rows($result);

	if( !$affected_rows )
	{
?>
	<script language="JavaScript" type="text/JavaScript">
		//window.alert('comment 를 삭제할 수 없습니다.<?=$affected_rows?>');
		history.go(-1);
	</script>
<?
	}else
	{
?>
	<script language="JavaScript" type="text/JavaScript">
		history.go(-1);
	</script>
<?
	}

############# footer #################

include INCLUDE_PATH."/footer.inc.php";
?>

