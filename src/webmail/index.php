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
<title>�ڶ� �� ����</title>
</head>

<body>
�ڶ� �� �����Դϴ�.<br />
���� ���񽺴� �߰����� �α����� �ʿ���մϴ�.<br />
<form name="mail_login" method="post" action="./login.php">
���̵� <input type="text" name="mail_id" size="10" maxlength="20" /><br />
��й�ȣ <input type="password" name="mail_password" size="10" maxlength="20" /><br />
<input type="submit" value="�α���">
</form>

</html>