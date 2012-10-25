<?php
include_once (realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");

/*
$config = JaramConfig::getConfig();

$params = array ('host'     => $config->database->host,
             'username' => $config->database->username,
             'password' => $config->database->password,
             'dbname'   => $config->database->dbname);

require_once 'Zend/Db/Adapter/Pdo/Mysql.php';

$dbAdapter = new Zend_Db_Adapter_Pdo_Mysql($params);

require_once 'Zend/Auth/Adapter/DbTable.php';
$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'jaram_users', 'user_id', 'user_password');
$authAdapter->setIdentity($_POST['username'])->setCredential(crypt($_POST["password"], substr($_POST["username"], 0, 2)));
$result = $authAdapter->authenticate();

echo "결과";
echo $result->getIdentity();

exit;
*/

if ($_POST) {
    echo "checking identical information...";
    flush();
    $dbo = ZendDB::getDBO();
    $passwd = crypt($_POST["password"], substr($_POST["username"], 0, 2));
    $row = $dbo->fetchRow("SELECT uid, user_id, user_name, user_email, user_homepage FROM jaram_users WHERE user_id=:username AND user_password=:password",	
    array('username'=>$_POST['username'], 'password'=>$passwd));

    if (!$row) {
        // ERROR MESSAGE
        //show_error_msg("아이디가 없거나 비밀번호가 틀립니다", "back");
        $logger->warning("잘못된 아이디 입력 - username : {$_POST['username']}, password : {$_POST['password']}");
    //	echo "아이디가 없거나 비밀번호가 틀립니다.";
    }

    // 세션의 보안을 위해서 몇가지 코드를 추가했습니다 by tasy 20030623
    // 사용법 :
    // include ("verify_session.inc.php"); 와 같은 형식으로 includes 디렉토리에 있는
    // verify_session.inc.php 를 인클루드 해서 사용하시면 됩니다.
    $time_started = md5(mktime());
    $secure_session_user = md5($_POST['username'].$_POST['password']);
    $_SESSION['jaram_session_user'] = $_POST['username'];
    $_SESSION['jaram_session_key'] = $time_started.$secure_session_user.getIPADDR().session_id();
    $_SESSION['jaram_current_session'] = $_POST['username']."=".$_SESSION['jaram_session_key'];

    // 필요한 변수는 추가시켜서 쓰세요.
    $_SESSION['jaram_user_uid'] = $row["uid"];
    $_SESSION['jaram_user_id'] = $row["user_id"];
    $_SESSION['jaram_user_name'] = $row["user_name"];

    // 자동 로그인 기능
    if ($_POST["auto_login"]) {
        setcookie(md5("jaram_auto_login"), crypt("check", substr($row["user_id"], 0, 2)), time() + 60 * 60 * 24 * 30, "/");
        setcookie(md5("jaram_user_id"), $row["user_id"], time() + 60 * 60 * 24 * 30, "/");
        // TODO 추가적인 확인 변수 추가
    }

    $start_page = $dbo->fetchOne("SELECT p.dir FROM jaram_programs AS p LEFT JOIN jaram_custom_menu AS c ON (p.pid = c.pid AND p.bid = c.bid) WHERE c.uid =:uid AND c.order_num = 1", array('uid'=>$row['uid']));

    if ($_REQUEST['url']) {
        $url = $_REQUEST['url'];
    }
    else if ($start_page) {
        $url = WEB_ABS_PATH.$start_page;
    }
    else {
        $url = WEB_ABS_PATH."/board/bbs.php?tableID=diary";
    }
    
    $logger->debug($url);

    /*
    print "<script language=\"JavaScript\">\n";
    print "window.location = \"".$url."\";\n";
    print "</script>\n";
    */

    echo("<meta http-equiv=\"Refresh\" content=\"0;URL=".$url."\">");
}