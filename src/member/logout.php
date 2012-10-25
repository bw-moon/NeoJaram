<?
include_once (realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");
session_unset();
session_destroy();


setcookie(md5("jaram_auto_login"), "", time() - 60 * 60 * 24 * 30, "/");
setcookie(md5("jaram_user_id"), "", time() - 60 * 60 * 24 * 30, "/");

if (isset($_GET['error'])) {
    header("location:".WEB_ABS_PATH."/index.php?error=".urlencode($_GET['error'])."&url=".urlencode($_GET['url']));
}

echo("<meta http-equiv=\"Refresh\" content=\"0;URL=".$_SERVER['HTTP_REFERER']."\">");
?>