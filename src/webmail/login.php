<?
session_start();
/*

$user_id = $_POST["mail_id"];
$user_password = $_POST["mail_password"];

include "class.WebMail.php";

$mail = new WebMail();
*/

$_SESSION["jaram_mail_id"] = $_POST["mail_id"];
$_SESSION["jaram_mail_password"] = $_POST["mail_password"];

?>
<script>
window.location = "mail_list.php";
</script>