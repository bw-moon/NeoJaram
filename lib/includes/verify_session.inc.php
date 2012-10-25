<?
session_start();
if ($_SESSION['jaram_current_session'] != $_SESSION['jaram_session_user']."=".$_SESSION['jaram_session_key'])
{
	header("location:".WEB_ABS_PATH."/member/logout.php?auth_msg=".urlencode("Your session has expired, please login again"));
}
?>