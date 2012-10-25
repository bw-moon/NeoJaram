<?
include_once realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php";

// 필수 데이터
if(!$_POST["id"] && !$_POST["name"] && !$_POST["pass"] && !$_POST["re_pass"] && !$_POST["email"] && !$_POST["phone1"] && !$_POST["phone2"])
{
	echo(" <script>
			  window.alert('중요 부분을 입력하지 않았습니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

// 암호
if( ($_POST["pass"] != $_POST["re_pass"]) || strlen($_POST["pass"]) < 4)
{
	echo(" <script>
			  window.alert('암호가 잘못 되었습니다. ')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

// DB 접속
dbconn();

// 유저 ID 중복 체크
$result = mysql_query("SELECT count(*) FROM jaram_users WHERE user_id='".$_POST["id"]."'");
$checkUser=mysql_result($result,0,0);
if($checkUser)
{
	echo(" <script>
			  window.alert('중복된 ID입니다.')
			  history.go(-1)
			 </script>
	   "); 
	exit;
}

$_POST[pass] = crypt($_POST[pass], substr($_POST[id], 0, 2));

// DB 입력
$result = mysql_query("INSERT INTO `jaram_freshman` VALUES ('', '$_POST[id]', '$_POST[name]', '$_POST[pass]', '$_POST[email]', '$_POST[homepage]', '$_POST[phone1]', '$_POST[phone2]', '$_POST[icq]', '$_POST[msn]', '$_POST[yim]', '$_POST[sign]');");

if($result)
{
	echo(" <script>
			  window.alert('홈페이지 가입 신청했습니다.')
			  close()
			 </script>
	   "); 
	exit;
}

echo(" <script>
		  window.alert('가입 신청에 실패했습니다.')
		  history.go(-1)
		 </script>
   "); 
?>