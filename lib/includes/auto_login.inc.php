<?
// 자동 로그인일 경우
if (!is_login() && ($_COOKIE[md5("jaram_auto_login")] == crypt("check", substr($_COOKIE[md5("jaram_user_id")], 0, 2)))) {
	// TODO 추가적인 확인 변수를 검사하는 부분 추가

	// connect the database
	dbconn();

	$query = "SELECT uid, user_id, user_name, user_email, user_homepage FROM jaram_users WHERE user_id='".$_COOKIE[md5("jaram_user_id")]."'";
	$result = mysql_query($query);

	$rows = @mysql_num_rows($result);

	if ($rows < 1) {
		// ERROR MESSAGE
		setcookie(md5("jaram_auto_login"), "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
		setcookie(md5("jaram_user_id"), "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
	
		print "<script language=\"JavaScript\">\n";
		print "alert(\"로그인 정보가 잘못되었습니다. 다시 로그인해주세요.\");\n";
		print "window.location.reload();\n";
		print "</script>\n";
		exit;
	}

	$row = mysql_fetch_array($result);

	// 세션의 보안을 위해서 몇가지 코드를 추가했습니다 by tasy 20030623
	// 사용법 :
	// include ("verify_session.inc.php"); 와 같은 형식으로 includes 디렉토리에 있는
	// verify_session.inc.php 를 인클루드 해서 사용하시면 됩니다.

	$time_started = md5(mktime());
	$secure_session_user = md5($_POST['member_id'].$_POST['password']);
	$_SESSION['jaram_session_user'] = $_POST['member_id'];
	$_SESSION['jaram_session_key'] = $time_started.$secure_session_user.getIPADDR().session_id();
	$_SESSION['jaram_current_session'] = $_POST['member_id']."=".$_SESSION['jaram_session_key'];

	// 필요한 변수는 추가시켜서 쓰세요.
	$_SESSION['jaram_user_uid'] = $row["uid"];
	$_SESSION['jaram_user_id'] = $row["user_id"];
	$_SESSION['jaram_user_name'] = $row["user_name"];
	$_SESSION['jaram_user_email'] = $row["user_email"];
	$_SESSION['jaram_user_homepage'] = $row["user_homepage"];

	// 자동 로그인 기능
	setcookie(md5("jaram_auto_login"), crypt("check", substr($row["user_id"], 0, 2)), time() + 60 * 60 * 24 * 30, "/");
	setcookie(md5("jaram_user_id"), $row["user_id"], time() + 60 * 60 * 24 * 30, "/");

	// 접속자 리스트에 추가
/*
	$shm_id = shmop_open(0xfff,"w", 0777, 1500);
	if(!$shm_id) {
		shmop_open(0xfff,"c", 0777, 1500);
	}
	// Get shared memory block's size
	$shm_size = shmop_size($shm_id);
	$user_list = shmop_read($shm_id, 0, $shm_size);
	if(!$user_list) {
	  echo "Couldn't read from shared memory block\n";
	  $user_list = "";
	}
	if(!strpos($user_list, $_SESSION['jaram_user_name']))
	 $user_list_new ="<br/>".$_SESSION['jaram_user_name'].$user_list;
	else 
		$user_list_new = $user_list;
	$shm_bytes_written = shmop_write($shm_id, $user_list_new, 0);
	if($shm_bytes_written != strlen($user_list_new)) {
//	   echo "Couldn't write the entire length of data\n";
	}
shmop_close($shm_id);
*/

}
?>