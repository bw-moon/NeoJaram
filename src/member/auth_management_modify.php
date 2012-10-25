<?
include_once(realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");
login_check();


	if( !$_GET["gid"] || !$_GET["pid"] || !$_GET["bid"] ) {
		show_error_msg("즐!", "back");
	}

	$auth_view = ($_POST['auth_view'])?1:0;
	$auth_read = ($_POST['auth_read'])?1:0;
	$auth_post = ($_POST['auth_post'])?1:0;
	$auth_comment = ($_POST['auth_comment'])?1:0;
	$auth_edit = ($_POST['auth_edit'])?1:0;
	$auth_delete = ($_POST['auth_delete'])?1:0;
	$auth_announce = ($_POST['auth_announce'])?1:0;
	$auth_vote = ($_POST['auth_vote'])?1:0;
	$auth_upload = ($_POST['auth_upload'])?1:0;

	dbconn();

	$query = "UPDATE jaram_auth_access SET auth_view='".$auth_view."',auth_read='".$auth_read."',auth_post='".$auth_post."',auth_comment='".$auth_comment."',auth_edit='".$auth_edit."',auth_delete='".$auth_delete."',auth_announce='".$auth_announce."',auth_vote='".$auth_vote."',auth_upload='".$auth_upload."' WHERE gid='".$_GET["gid"]."' AND pid='".$_GET["pid"]."' AND bid='".$_GET["bid"]."'";

	$result = mysql_query($query);

	if ($result) {
		show_error_msg("수정OK!", "back");
		exit;
	} else {
		show_error_msg("뷁!", "back");
		exit;
	}
?>