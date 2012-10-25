<?
// index.php
session_start();

if ($_SESSION["jaram_mail_id"]) {
	header("Location: mail_list.php");
	exit;
}

?>
<html>

<head>
<title>자람 웹 메일</title>
</head>

<body>
자람 웹 메일입니다.<br />
메일 서비스는 추가적인 로그인을 필요로합니다.<br />
<form name="mail_login" method="post" action="./login.php">
아이디 <input type="text" name="mail_id" size="10" maxlength="20" /><br />
비밀번호 <input type="password" name="mail_password" size="10" maxlength="20" /><br />
<input type="submit" value="로그인">
</form>

</html>