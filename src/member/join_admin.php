<?
include_once (realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");

// 중간 체크
if(!$_POST["check"] || !$_POST["groupid"] || !$_POST["groupmyid"] || !$_POST["pass"] || !$_POST["fnum"])
{
	echo(" <script>
			  window.alert('입력이 불충분합니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}
// 암호 체크
if($_POST["pass"]!="qkqwy")
{
	echo(" <script>
			  window.alert('암호가 틀렸습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

// DB 접속
dbconn();

$fnum=$_POST["fnum"];		// 새내기 기수
$myid=$_POST["groupmyid"];	// 시작될 id 값
$fgid=$_POST["groupid"];	// 새내기 그룹 ID

foreach($_POST["check"] as $users)
{
	$result = mysql_query("SELECT * FROM `jaram_freshman` WHERE uid=".$users);
	$data=mysql_fetch_array($result);
	echo "$data[2] 처리중 : ";

	// 유저 정보 입력
	$result = mysql_query("INSERT INTO `jaram_users` VALUES ('$myid', '$data[1]', '$data[2]', '$data[3]', '$fnum', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]','','', NULL , NULL );");
	if($result)
		echo "&nbsp;1번 성공&nbsp;";
	else
	{
		echo "&nbsp;1번 실패&nbsp;";
		continue;
	}
	// 자신의 그룹 테이블
	$result = mysql_query("INSERT INTO `jaram_groups` VALUES ('$myid', '".$data["user_name"]."', NULL );");
	if($result) echo "&nbsp;2번 성공&nbsp;"; else echo "&nbsp;2번 실패&nbsp;";
	// 자신이 속한 그룹 테이블
	// 자기 자신의 그룹, 기수, 자람 회원
	$result = mysql_query("INSERT INTO `jaram_user_group` VALUES ('', '$myid', '$myid','');");
	if($result) echo "&nbsp;3번 성공&nbsp;"; else echo "&nbsp;3번 실패&nbsp;";
	$result = mysql_query("INSERT INTO `jaram_user_group` VALUES ('', '$fgid', '$myid','');");
	if($result) echo "&nbsp;4번 성공&nbsp;"; else echo "&nbsp;4번 실패&nbsp;";
	$result = mysql_query("INSERT INTO `jaram_user_group` VALUES ('', '1002', '$myid','');");
	if($result) echo "&nbsp;5번 성공&nbsp;"; else echo "&nbsp;5번 실패&nbsp;";
	mysql_query("DELETE FROM `jaram_freshman` WHERE uid=".$users);

	
	$mail_info[from_name] = "자람";
	$mail_info[from_address] = "mun0309@chol.com";
	$mail_info[to_name] = $data[2];
	$mail_info[to_address] = $data[4];
	$mail_info[message] = "안녕하세요?<br><br>\n
	자람에서 보내드리는 회원가입인증 메일입니다.<br>\n
	정상적으로 자람홈페이지(<a href=\"http://jaram.org\" target=\"_blank\">)의 멤버가입이 완려되었습니다.<br>\n
	자람 홈페이지를 정상적으로 사용할 수 있으며, 가입하셨을때 입력하셨던 아이디와 암호를 이용하여 로그인 하시면 됩니다.";
	$mail_info[subject] = "자람홈페이지 가입이 완료되었습니다.";
	$myid++;
}


?>