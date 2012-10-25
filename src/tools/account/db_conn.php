<?

include('./info.php');	//info 파일 연결

$dbconnect=mysql_connect("localhost", "$mysql_id", "$mysql_password") or die("DB서버 연결에 실패하였습니다.");	//DB 접속

mysql_select_db("$mysql_id", $dbconnect);	//DB 선택

?>