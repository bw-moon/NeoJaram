<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> New Document </title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
</head>

<body>
<?
include_once realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php";
dbconn();

$query = "SELECT * FROM jaram_users WHERE user_number > 0 ORDER BY user_number, user_name DESC";

$result = mysql_query($query);

while ($rows = mysql_fetch_array($result)) {
	$mail_info['from_name'] = "자람";
	$mail_info['from_address'] = "deadwi@chol.com";
	$mail_info['to_name'] = $rows['user_name'];
	$mail_info['to_address'] = $rows['user_email'];
	$mail_info['subject'] = "한양대학교 자람에서 보내드리는 소식 메일입니다.";
	$mail_info['message'] = nl2br("안녕하세요. 자람 현 학회장을 맡고 있는 18th 조지훈입니다.
2004년 가을 엠티가 다른 행사와 겹치는 바람에 연기되었습니다.

2004년 11월 26(금), 27(토)일로 옮겼습니다.

장소는 그대로 오이도입니다.
");

	//show($mail_info);

	if (SEND_HTML_MAIL($mail_info)) {
		echo $rows['user_name']."성공<br>\n";
	}
	else {
		echo $rows['user_name']."-->실패<br>\n";
	}

}

?>
</body>
</html>
