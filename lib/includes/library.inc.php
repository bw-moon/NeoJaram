<?php
/**
 * 자람 내부에서 공통적으로 사용되는 변수나 함수들을 모아둔다.
 * 함수의 경우 각 함수 부분에 함수의 사용법이나 입력하는 변수설명 등을 적어주도록 하고
 * 변수의 경우는 설명을 붙여서 이해가 쉽게 만든다.
 **/
session_start();

error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// 자주 사용하는 클래스들을 쉽게 부를 수 있게
function __autoload($class_name) {
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.$class_name.".class.php";
}

define(STATUS, "dev");
$CONFIG = getConfig(STATUS);

// 홈페이지 절대경로
$d = DIRECTORY_SEPARATOR;
$context_name = $CONFIG->context_name;
$config_abs_path = dirname(__FILE__);
$homepage_path = realpath($config_abs_path.'/../../src');
$web_abs_path = "/".$context_name;

define(INCLUDE_PATH, dirname(__FILE__));
define(PROGRAM_ROOT, realpath(INCLUDE_PATH.'/../../'));
define(LIBRARY_PATH, realpath(PROGRAM_ROOT.'/lib/'));
define(HOMEPAGE_PATH, realpath(PROGRAM_ROOT.'/src/'));
define(WIDGET_PATH, realpath(PROGRAM_ROOT.'/data/widget/'));
define(WEB_ABS_PATH, $web_abs_path);
define(ADMIN_GROUP, 1010);
define(GUEST_GROUP, 1300);


// 유용한 데이터들
$messenger_type = array('msn'=>'MSN메신저', 'nate' => '네이트온', 'gtalk'=>'구글토크');


// 스마티 라이브러리 설정
define(JARAM_SMARTY_DIR, LIBRARY_PATH."{$d}Smarty-".$CONFIG->smarty_ver."{$d}libs{$d}");
set_include_path(get_include_path() . PATH_SEPARATOR . JARAM_SMARTY_DIR);

// 로거
if (!defined('LOG_LEVEL')) {
	define ('LOG_LEVEL', PEAR_LOG_DEBUG);
}
$logger = getLogger('GlobalLogger', PEAR_LOG_DEBUG);

// 타임존 설정
setlocale(LC_TIME, "ko_KR");
date_default_timezone_set('ROK'); 

// 캐쉬타임
$CACHE_SIDETOOLS = 600;
$CACHE_INDEX = 1;
$CACHE_RSSFEED = 60 * 3 * 60; // 3시간간 유지
//$CACHE_RSSFEED = 1; // 20분간 유지

// DB커넥션
$DB_CONNECTION = 0;



// 표준 예외 오류 로그로 넘기기
set_error_handler('errorHandler');


// Mysql연결 후, DB연결 실패시 메시지 전송
function dbconn() {
    $GLOBALS['logger']->warning("old db function called!! {$_SERVER['REQUEST_URI']}");
    $config = JaramConfig::getConfig();
    if ($GLOBALS['DB_CONNECTION'] == 0) {
        $connect = mysql_connect($config->database->host, $config->database->username, $config->database->password) or die("<b>MySQL접속에 실패했습니다</b>: " . mysql_error());
        mysql_select_db($config->database->dbname, $connect) or die("<b>DB연결에 실패했습니다</b>: " . mysql_error());
        $GLOBALS['DB_CONNECTION']++;
    }
}

// DB연결을 끊는다.
function dbclose() {	
	mysql_close();
}

function getConfig($status) {
    return JaramConfig::getConfig($status);
}

// 로거를 리턴
function getLogger($ident = "GlobalLogger", $logLevel = LOG_LEVEL) {
    return JaramLogger::getLogger($ident, $logLevel);
}

// 서브 메뉴를 리턴
function get_menuinfo($ID) {
	$dbo = ZendDB::getDBO();
	
	$query = "SELECT * FROM jaram_programs WHERE (main_menu=:menu AND order_num > 0) ORDER BY order_num ASC;";

	$result = $dbo->fetchAll($query, array('menu'=>$ID['main_menu']));
	
	$subMenu = "<div id=\"SubMenu\" class=\"floatClear side_container\">\n<h2>".HTMLSpecialChars($ID['main_menu'], ENT_QUOTES)."</h2>\n";
	foreach ($result as $row) {
		$menu = HTMLSpecialChars($row['sub_menu'], ENT_QUOTES);

		if ($GLOBALS['BOARD_RECENT_COUNT'][$row->bid] > 0) {
			$menu .= " (".$GLOBALS['BOARD_RECENT_COUNT'][trim($row['bid'])].")";
		}
		// http가 포함된 주소일 경우 그냥 표시해주고, 아니면 context 경로를 포함한 상태경로로 표시
		if (preg_match('/http/i', $row['dir'])) {
			$url = $row['dir']; 
		}
		else {
			$url = WEB_ABS_PATH.$row['dir'];
		}

		if ($ID['pid'] == $row['pid'] && $ID['bid'] == $row['bid'])
			$class="NavId";
		else
			$class ="Nav";

		$subMenu .= "<div id=\"".$class."\"><a href=\"".$url."\">".$menu."</a></div>\n";
	}

	$subMenu .= "</div>\n";
	return array("menu" => $subMenu, "main_menu" => $ID['main_menu'], "sub_menu" => $ID['sub_menu']);
}

# 리눅스의 심볼릭 링크를 윈도우에서 사용
function search_link($link) { 
	if (is_file($link)) {
		$handle = fopen($link, "r");
		$content = explode(":", fread($handle, 1024));
		if (count($content) > 1) {
			$dir = dirname($link);
			return search_link($dir.DIRECTORY_SEPARATOR.trim($content[1]));
		} else {
			return false;
		}
	} elseif (is_dir($link)) {
		return $link;
	} else {
		return false;
	}
}


/*****************
 입력된 스트링이 비었거나 파일형식(*.*)이면 false
 나머지는 true
 ******************************/
function isDir($path) {
	return !empty($path) && !preg_match('/\./', $path);
}


// 표준 예외와 오류를 로거로 남기기
function errorHandler($code, $message, $file, $line) {
	$logger = getLogger("systemLogger");
	switch ($code) {
		case E_WARNING:
		case E_USER_WARNING:
			$priority = PEAR_LOG_WARNING;
			break;
		case E_NOTICE:
		case E_USER_NOTICE:
			$priority = PEAR_LOG_NOTICE;
			return;
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$priority = PEAR_LOG_ERR;
			break;
		default:
			$priority = PEAR_LOG_INFO;
			return;
	}
	$logger->log($message . ' in ' . $file . ' at line ' . $line, $priority);
}

/*
 * 현재 위치를 판별해서 프로그램정보를 리턴한다.
 */
function get_program_info($url = "") {	
    if(empty($url))
        $url = $_SERVER["PHP_SELF"];
    else
        $url = str_replace("http://", "", $url);

	$dirs = split("/", $url);
	$context_depth = count(split("/",$GLOBALS['context_name']));
	$mainDir = $dirs[1+$context_depth];
    $subDir = $dirs[2+$context_depth];

    if (!isset($_GET["tableID"]))
        $tableID = $_POST["tableID"];
    else
        $tableID = $_GET["tableID"];

	$dbo = ZendDB::getDBO();
    if ($mainDir == "board") {
		$result = $dbo->fetchRow("SELECT program_id, pid, bid, main_menu, sub_menu FROM jaram_programs WHERE bid=:bid", array('bid'=>$tableID)	 );
    } else {
		if (isDir($subDir)) {
			$dir = sprintf("/%s/%s", $mainDir, $subDir); 
		} else {
			$dir = sprintf("/%s", $mainDir);
		}
		$result = $dbo->fetchRow("SELECT program_id, pid, bid, main_menu, sub_menu FROM jaram_programs WHERE dir=:dir", array('dir'=>$dir));
    }
    if (empty($result)) {
        return array();
        //show_error_msg("잘못된 접근입니다!", "back");
        
    }

    return array(
				"program_id" => $result["program_id"],
                "pid" => $result["pid"], 
                "bid" => $result["bid"],
                "main_menu" => $result["main_menu"],
                "sub_menu" => $result["sub_menu"]
	);
}

function jaram_warn($msg, $msg_id = 0,$display = false) {
	return one_line_msg("exclamation", $msg, $msg_id,$display);
}

function jaram_info($msg, $msg_id = 0,$display = false) {
	return one_line_msg("information", $msg,$msg_id, $display);
}

function jaram_error($msg, $msg_id = 0,$display = false) {
	return one_line_msg("error", $msg, $msg_id,$display);
}

function one_line_msg($type, $msg, $msg_id, $display = false) {
	$style = ($display) ? "" : " style=\"display:none\""; 

    $one_line_tpl = new JaramSmarty();
    $one_line_tpl->assign('type', $type);
    $one_line_tpl->assign('msg', $msg);
    $one_line_tpl->assign('msg_id', $msg_id);
    $one_line_tpl->assign('style', $style);
    return $one_line_tpl->fetch('common/one_line_msg.tpl');
}

function get_new_icon ($date, $diff = "-1 day") {
	$time = strtotime($date);
	if ($time > strtotime($diff)) {
		return "<img src=\"".WEB_ABS_PATH."/images/icons/new.gif\" border=\"0\" alt=\"new\"/>";
	}
}


// 확인 메시지
function show_confirm_msg($msg)
{
    $msg_2 = $msg;

    $msg = nl2br($msg);

    $location = $_SERVER['REQUEST_URI'];

	echo "<div id=\"message\" class=\"message\">";
	echo "<p>$msg</p><hr/><p>";
	echo "<a href=\"".$location."&amp;answer=yes\" class=\"image\"><img src=\"".WEB_ABS_PATH."/images/button/btn_ko_yes.gif\" border=\"0\" alt=\"yes\"/></a> ";
    echo "<a href=\"".$location."&amp;answer=no\" class=\"image\"><img src=\"".WEB_ABS_PATH."/images/button/btn_ko_no.gif\" border=\"0\" alt=\"yes\"/></a>";
    echo "</p></div>";
}


function show_std_msg($msg, $msg_type = "std", $url = "", $location = "")
{
    $add = "<br/><br/>";
    $msg_2 = $msg;

    if(!empty($url))
        $add .= "1초후 $url 페이지로 자동으로 이동합니다.<br/>";

    $add .= "<a href=\"javascript:history.go(-1);\">backward link</a>";
    $msg .= $add;
    $msg = nl2br($msg);

    if($msg_type == "std") {
        if (empty($location)) $location = $_SERVER['REQUEST_URI'];

        echo("<div class=\"message\">Message<hr/>$msg</div>");
        if($url != "")
        {
            $url = HTMLSpecialChars($url, ENT_QUOTES);
            echo "<meta http-equiv=\"refresh\" content=\"1;url=$url\"/>";
        }

    }
    elseif($msg_type == "back") {
        echo("<script type=\"text/javascript\" language=\"JavaScript\">window.alert(\"$msg_2\");history.go(-1);</script>");
        include_once (INCLUDE_PATH."/footer.inc.php");
        exit();
    }
}

/*
 * 에러메시지 출력함수
 * 사용방법 : $msg는 메시지 내용
 * $msg_type는 메시지 출력 방식이며, std는 표준에러출력 back는 
 * 자바스크립트를 이용한 뒤로 가는 에러 출력이다.
 * 이동시키고자 하는 페이지가 있으면, $url 부분에 넣어주도록 하고 
 * $location 에는 $PHP_SELF."?".$QUERY_STRING 과 같이 넣어준다.
 * ex) show_error_msg("시험으로 쓰는 에러 메시지 입니다.", "std"), 
 * "http://jaram.org", $PHP_SELF."?".$QUERY_STRING);
 * $url 과 $location을 안적어도 되며, 적지 않을 경우 history.back 가 작용합니다.
 */
function show_error_msg($msg, $msg_type = "std", $url = "", $location = "")
{
    $add = "<br/><br/>";
    $msg_2 = $msg;

    if(!empty($url))
        $add .= "2초후 $url 페이지로 자동으로 이동합니다.<br/>";

    $add .= "계속 이런 에러가 발생할 경우 위의 내용을 메일로 ".show_email(1406,WEBMASTER_MAIL)." 알려주시면 감사하겠습니다.\n\n<a href=\"javascript:history.go(-1);\">backward link</a>";
    $msg .= $add;
    $msg = nl2br($msg);

    if($msg_type == "std") {
        if (empty($location)) $location = $_SERVER['REQUEST_URI'];

		echo "<div class=\"error\">";
        echo("<p>address : <a href=\"$location\">$location</a></p><hr/><p>$msg</p>");
		echo "</div>";
		if($url != "")
        {
            $url = HTMLSpecialChars($url, ENT_QUOTES);
            echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\"/>";
        }
    }
    elseif($msg_type == "back") {
        echo("<script type=\"text/javascript\" language=\"JavaScript\">window.alert(\"$msg_2\");history.go(-1);</script>");
        include_once (INCLUDE_PATH."/footer.inc.php");
		exit;
    }
}


function auto_link($text) { 
	$ret = eregi_replace("([[:alnum:]]+)://([a-z0-9\200-\377\\_\./~@:?=%&,#\-]+)", "<a href=\"\\1://\\2\" target=\"_blank\">\\1://\\2</a>", $text); 
	$ret = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", "<a href='mailto:\\1' target=\"_self\">\\1</a>", $ret); 
	return $ret; 
}

// 20020409 형식의 날짜에서 네자리 두자리 두자리씩 나누어서 리턴
function get_each_date($date)
{
    $data = trim($date);
    $rd['year'] = substr($date, 0, 4);
    $rd['month'] = substr($date, 4, 2);
    $rd['day'] = substr($date, 6, 2);

    return $rd;
}

function get_select($array, $name, $val, $data, $chk = "nothing", $tail = "class=\"inputtext\"") {
    $rv = "<select name=\"".$name."\" ".$tail.">";
    foreach ($array as $key => $value) {
        if ($value[$val] == $chk) {
            
            $chk_add = "selected=\"selected\"";
        } else {
            $chk_add = "";
        }
        $rv .= "<option value=\"".$value[$val]."\" ".$chk_add.">".$value[$data]."</option>\n";
    }
    $rv .= "</select>\n";
    return $rv;
}




/**
 * 결과를 배열로 넘겨주는 부분
 */

// 주어진 범위의 그룹의 gid, group_name을 배열로 리턴
function get_group_array($start = 0, $end = 10000) {
    if($start != 0 && $end != 10000) {
        $condition = "WHERE :start <=  gid AND gid <= :end";
    } elseif ($start != 0 || $end != 10000) {
        $condition_sub = ($start != 0) ? ":start <= gid" : "gid <= :end";
        $condition = "WHERE ".$condition_sub;
    }
    $query = "SELECT gid,group_name FROM jaram_groups ".$condition." ORDER BY gid ASC;";
	$reault = ZendDB::getDBO()->fetchAll($query, array('start'=>$start, 'end'=>$end));
    return $rv;
}


// uid를 받아 gid의 배열을 넘겨준다.
function get_gids($uid = null) {
	if (is_null($uid) && is_login()) {
		$uid = $_SESSION['jaram_user_uid'];
	}
	$result = ZendDB::getDBO()->fetchCol("SELECT gid FROM jaram_user_group WHERE uid=:uid", array('uid'=>$uid));
	return $result;
}

// 프로그램의 목록을 배열로 리턴
function get_program_array() {
    $query = "SELECT * FROM jaram_programs";
    $result = ZendDB::getDBO()->fetchAll($query);
    $rv = array();

	foreach ($result as $rows) {
		array_push($rv, 
			array(
				"pid" =>$rows['pid'].":".$rows['bid'],
				"sub_menu" => empty($rows['sub_menu']) ? $rows['main_menu'] : $rows['sub_menu']
			)
		 );
	}
    return $rv;
}

function get_program_options () {
	$result = ZendDB::getDBO()->fetchPairs("SELECT program_id, IF(LENGTH(sub_menu)=0, main_menu, CONCAT('└ ', sub_menu)) AS program_name FROM jaram_programs ORDER BY main_menu ASC, order_num ASC");
    return $result;
}

// gid(user group)를 입력받아 그룹정보를 array로 넘겨줌
function get_groups_from_gid($uid)
{
    $gids = get_gids($uid);
    if (count($gids) > 0) {
        for ($i = 0; $i < count($gids); $i++)
            $ra[$i] = get_group_info($gids[$i]);
        return $ra;
    } else {
        return false;
    }
}


// 그룹 선택에 사용되는 옵션을 리턴
function get_group_options($uid = null) {
    $config = JaramConfig::getConfig();

	// 개인 그룹을 제외한 그룹을 보여줌
	return ZendDB::getDBO()->fetchPairs("SELECT gid, group_name FROM jaram_groups  WHERE gid NOT BETWEEN :group_private_start AND :group_private_end ORDER BY gid ASC", array('group_private_start' => $config->group_private_start, 'group_private_end' => $config->group_private_end));
}

// gid를 입력받아 그룹정보 array로 넘겨줌
function get_group_info($gid) {
	$result = ZendDB::getDBO()->fetchRow("SELECT * FROM jaram_groups WHERE gid = :gid", array('gid'=>$gid));
    return $result;
}

function makePairs($arr) {
    $result = array();
    foreach ($arr as $row) {
        $result[$row[0]] = $row[1];
    }
    return $result;
}


// 권한을 체크한다
// @return 연과배열
function get_auth($PROGRAM_INFO)
{
    $auth["auth_view"] = 0;
    $auth["auth_read"] = 0;
    $auth["auth_post"] = 0;
    $auth["auth_comment"] = 0;
    $auth["auth_edit"] = 0;
    $auth["auth_delete"] = 0;
    $auth["auth_announce"] = 0;
    $auth["auth_vote"] = 0;
    $auth["auth_upload"] = 0;

    // 세션 변수에 uid가 등록되지 않았을 경우 guest로 설정
    $config = JaramConfig::getConfig();
    $uid = ($_SESSION["jaram_user_uid"]) ? $_SESSION["jaram_user_uid"] : $config->group_guest_id;

    if (!empty($PROGRAM_INFO['pid'])) {
        $pid = $PROGRAM_INFO['pid'];
        $bid = $PROGRAM_INFO['bid'];

        foreach (get_gids($uid) as $gid) {
            $query = "SELECT * FROM jaram_auth_access WHERE gid=:gid AND pid=:pid ";
            if (!empty($bid)) {
                $query .= " AND bid=:bid ";
                $params = array('gid'=>$gid, 'pid'=>$pid, 'bid'=>$bid);
            } else {
                $params = array('gid'=>$gid, 'pid'=>$pid);
            }
//            $GLOBALS['logger']->debug($gid.'|'.$pid.'|'.$bid);
            $result = ZendDB::getDBO()->fetchAll($query,$params);

			foreach ($result as $rows) {
				$auth["auth_view"] += $rows["auth_view"];
				$auth["auth_read"] += $rows["auth_read"];
				$auth["auth_post"] += $rows["auth_post"];
				$auth["auth_comment"] += $rows["auth_comment"];
				$auth["auth_edit"] += $rows["auth_edit"];
				$auth["auth_delete"] += $rows["auth_delete"];
				$auth["auth_announce"] += $rows["auth_announce"];
				$auth["auth_vote"] += $rows["auth_vote"];
				$auth["auth_upload"] += $rows["auth_upload"];
			} // end-of-foreach
        } // end-of-if
        return $auth;
    } // end-of-if
    return $auth;
} // end-of-function


// 그룹관리자 정보를 리턴
function get_group_admin_info($gid) {
    $sql = "SELECT * FROM jaram_user_group AS a LEFT JOIN jaram_users AS b ON a.uid = b.uid WHERE a.gid=:gid AND a.status='o';";
    return ZendDB::getDBO()->fetchAll($sql, array('gid'=>$gid));
}

// 게시판의 최신글을 ul, li리스트로 보여줌
function show_article_list($data, $tableID) {
    if (count($data) > 0)
    {
        $rv = "<ul>\n";
        for ($i = 0; $i < count($data); $i++)
        {
            $link= "<a href=\"".WEB_ABS_PATH."/board/view.php?tableID=".$tableID."&amp;id=".$data[$i]['id']."\">".$data[$i]['title']."</a>";
            $rv .= sprintf("<li><span class=\"time\">%s</span> %s</li>\n", substr($data[$i]['date'], 5, 5), $link);
        }
        $rv .= "</ul>\n";
        return $rv;
    } else {
        return "";
    }
}

// 최신 게시글 리스트를 배열로 리턴
function recent_article_list($tableID, $count = 5)
{
    return ZendDB::getDBO()->fetchAll("SELECT id,title,note,date FROM jaram_board WHERE bid=:bid ORDER BY id DESC LIMIT {$count}", array('bid'=>$tableID));
}

/**
 * 결과를 boolean값으로 넘겨주는 부분
 */

// 그룹의 관리자면 true, 아니면 false
function is_group_admin($uid, $gid = "") {
    if (empty($gid))
        $sql = "SELECT * FROM jaram_user_group WHERE uid=:uid AND status='o';";
    else 
        $sql = "SELECT * FROM jaram_user_group WHERE uid=:uid AND gid=:gid AND status='o';";

	return ZendDB::getDBO()->fetchAll($sql, array('uid'=>$uid, 'gid'=>$gid));
}

// uid와 gid를 입력받아 uid가 속해 있는 그룹에 gid의 그룹이 있는지를 확인하여 true, false로 리턴
function in_group($uid, $gid) {
    return in_array($gid, get_gids($uid));
}

function is_login () {
    return isset($_SESSION['jaram_user_uid']);
}

/*
 * 관리자 그룹에 속하는 사용자인지 체크
 * 문병원(mun0309@chol.com)
 * 2005.03.08
 */
function is_admin() {
//    return (in_group($_SESSION['jaram_user_uid'], ADMIN_GROUP) || in_group($_SESSION['jaram_user_uid'], 1001));
    return true;
}


/**
 * 현재 실행중인 부분이 실행권한의 어떤 부분에 해당하는지를 기술
 * 권한이 올바르면 true 권한이 없으면 false 리턴
 * @ $auth_tag 검사부분을 |로 이어진 스트링으로 입력. 예) "view|write|upload"
 */
function chk_auth($auth_tag) {
    $auth_array = get_auth($GLOBALS['PROGRAM_INFO']);
    $auth_check = explode("|",$auth_tag);

    foreach ($auth_check as $auth) {
        if ($auth_array[trim($auth)] == 0) {
            return false;
        }
    }
    return true;
}

/**
 * String 으로 정보를 리턴하는 함수
 */
// 사용자의 ip를 리턴
function getIPADDR()
{
    return $_SERVER['REMOTE_ADDR'];
}


// byte로 파일 사이즈를 입력하면 보기 좋은 결과로 리턴
function getFancyFileSize($param) {
	if (is_array($param)) {
		$byte = $param['byte'];
	} else {
		$byte = $param;
	}

	if (!$byte) {
		$byte = 0;
	}
	if ($byte < 1024)
		return $byte."Byte";
	else if ($byte < 1024*1024)
		return sprintf("%0.1fKB", $byte/1024);
	else if ($byte < 1024*1024*1024) 
		return sprintf("%0.1fMB", $byte/1024/1024);
	else if ($byte < 1024*1024*1024*1024) 
		return sprintf("%0.1fGB", $byte/1024/1024/1024);
	else 
		return $byte."Byte";
	
}


// html 테그를 제거하여 리턴
function stripeentag($msg,$tag,$attr) { 
    $lengthfirst = 0; 
    while (strstr(substr($msg,$lengthfirst),"<$tag ")!="") 
    { 
        $imgstart = $lengthfirst + strpos(substr($msg,$lengthfirst), "<$tag "); 
        $partafterwith = substr($msg,$imgstart); 
        $img = substr($partafterwith,0,strpos($partafterwith,">")+1); 
        $img = str_replace(" =","=",$msg); 
        $out = "<$tag";   
        for($i=1;$i<=count($atr);$i++) 
        { 
             $val = filter($img,$attr[$i]."="," "); 
             if(strlen($val)>0) $attr[$i] = " ".$attr[$i]."=".$val; 
             else $attr[$i] = ""; 
             $out .= $attr[$i]; 
        } 
        $out .= ">"; 
        $partafter = substr($partafterwith,strpos($partafterwith,">")+1); 
        $msg = substr($msg,0,$imgstart).$out.$partafter; 
        $lengthfirst = $imgstart+3; 
    } 
    return $msg; 
}

// 안전한 html스트링을 리턴
function realsafehtml($str) {
    // Don't do anything if there's no difference or if the original string is empty
    $oldstr = "";

    while($str != $oldstr) // Loop until it got no more effect
    {
    $oldstr = $str;
          //nuke script and header tags and anything inbetween
          $str = preg_replace("'<script[^>]*?>.*?</script>'si", "", $str);
          $str = preg_replace("'<head[^>]*?>.*?</head>'si", "", $str);
          
          //listed of tags that will not be striped but whose attributes will be
          $allowed = "br|b|i|p|u|a|center|hr";
          //start nuking those suckers. don you just love MS Word's HTML?
          $str = preg_replace("/<((?!\/?($allowed)\b)[^>]*>)/xis", "", $str);
          $str = preg_replace("/<($allowed).*?>/i", "<\\1>", $str);
    }

      return $str;
}


// 이메일 주소를 가공해서 sendmessage로 연결시키는 함수
function show_email($uid, $email)
{
    $email = trim(eregi_replace("@", " at ", $email));
    $email = "<a href=\"".WEB_ABS_PATH."/?page=sendmessage&amp;touser=".$uid."\" title=\"이곳을 클릭하면 웹메일로 연결됩니다\" class=\"sub\">".$email."</a>";
    return $email;
}

// 홈페이지 주소로 구성
function show_homepage($homepage)
{
    $homepage = trim(eregi_replace("http://", "", $homepage));
    return $homepage;
}

// 특정 게시판의 특정 게시물 제목 얻어 오기
function get_board_title($tableID, $id)
{
    $dbo = ZendDB::getDBO();
    
    $sql = "SELECT title FROM jaram_board WHERE id=:id AND bid=:bid limit 1";
    
    $result = $dbo->fetchAll($sql, array('id'=>(int)$id, 'bid'=>$tableID));

    if (isset($result[0]['title'])) {
    	$title = $result[0]['title'];
    } else {
    	$title = "제목없음";
    }
    return HTMLSpecialChars($title);
}

function get_microtime($old, $new)
{
    // 주어진 문자열을 나눔 (sec, msec으로 나누어짐) 
    $old = explode(" ", $old);
    $new = explode(" ", $new);
	$time = array();
    $time['msec'] = $new[0] - $old[0];
    $time['sec']  = $new[1] - $old[1];

    if($time['msec'] < 0) {
    $time['msec'] = 1.0 + $time['msec'];
    $time['sec']--;
    }

    $time = sprintf("%.2f", $time['sec'] + $time['msec']);

    return $time;
}

function icon($filename) {
    $ext = strtolower(array_pop(explode('.',$filename)));
    return "<img src='".WEB_ABS_PATH."/images/icon/$ext.gif' onError='this.src=\"".WEB_ABS_PATH."/images/icon/default.gif\"'>";
}

function _urlencode($url) {
  #$name=urlencode(strtr($url,"+"," "));
  #return preg_replace(array('/%2F/i','/%7E/i','/%23/'),array('/','~','#'),$name);
  return preg_replace("/([^a-z0-9\/\?\.\+~#&:;=%]{1})\-/ie","'%'.strtoupper(dechex(ord('\\1')))",$url);
}


// 홈페이지 루트의 recent_count.txt파일을 읽어서 5분을 주기로 갱신되는
// 오늘 올라온 글 수를 구함.
function recent_board_count()
{
	if (file_exists(HOMEPAGE_PATH."/recent_count.txt")) {
		$fcontents = file(HOMEPAGE_PATH."/recent_count.txt");
		while (list ($line_num, $line) = each ($fcontents)) {
			$each_count = explode(" ", $line);
			$rv[$each_count[0]] = trim($each_count[1]);
		}
		return $rv;
	}
}


function kstrcut($str, $len, $suffix = "...") 
{
    if ($len >= strlen($str)) return $str;
    $klen = $len - 1;
    while(ord($str[$klen]) & 0x80) $klen--;
    return substr($str, 0, $len - (($len + $klen + 1) % 2)) . $suffix;
}

function make_query_str($additional_query) {
    parse_str($_SERVER['QUERY_STRING'], $parsed);
	
	if (is_string($additional_query)) {
		parse_str($additional_query, $query_arr);
	} else {
		$query_arr = $additional_query;
	}

    $merged = array_merge($parsed, $query_arr);
    return http_build_query($merged, '', '&');
}

function show_book_img($isbn)
{
    $twoIsbn=substr($isbn,0,2);
    if($twoIsbn==89)
        $outputImg="<a href=\"http://www.aladdin.co.kr/catalog/book.asp?ISBN=".$isbn."\" target=\"_blank\"><img border=\"1\" src=\"http://www.aladdin.co.kr/Cover/".$isbn."_1.gif\"/ alt=\"".$isbn."\"></a>";
    else
        $outputImg="<a href=\"http://www.amazon.com/exec/obidos/ISBN=".$isbn."\" target=\"_blank\"><img border=\"1\" src=\"http://images.amazon.com/images/P/".$isbn.".01.MZZZZZZZ.gif\" alt=\"".$isbn."\"/></a>";
    return $outputImg;
}


function get_dday($date)
{
    $date = get_each_date($date);
    $time = mktime(24,0,0,$date['month'],$date['day'],$date['year']);
    return (int)((time() - $time) / (3600 * 24));
}


/**
 * 행동을 취하는 함수 (void return)
 */

function p_redirect($location, $time = 0)
{
    echo "<meta http-equiv=\"refresh\" content=\"".$time.";url=".$location."\"/>";
}


// 현재 BBS를 모니터 중인 회원에게 메일을 발송한다
function monitor_bbs($articleInfo) {
    dbconn();
    $query = "SELECT user_email FROM jaram_bbs_monitor AS m LEFT JOIN jaram_users AS u USING(uid) WHERE m.pid=" . $articleInfo['pid'] . " and m.bid=\"" . $articleInfo['bid'] . "\" and u.user_email != \"\"";
    
    if (empty($articleInfo["bid"]))	$query .= " AND m.bid='".$articleInfo["bid"]."' ";

    $result = mysql_query($query);

    if (@mysql_num_rows($result) > 0) {

        $MAIL_ADDRESSES = "";
        while ($rows = mysql_fetch_array($result)) {
            $MAIL_ADDRESSES .= $rows["user_email"] . ",";
        }
        substr($MAIL_ADDRESSES, 0, -1);

        $url = "http://www.jaram.org/".WEB_ABS_PATH."board/view.php?tableID=" . $articleInfo['bid'] . "&id=".$articleInfo['id'];
        $mail_info['from_name'] = "자람 보드 모니터";
        $mail_info['from_address'] = WEBMASTER_MAIL;
        $mail_info['to_name'] = "구독자";
        $mail_info['to_address'] = $MAIL_ADDRESSES;
        $mail_info['subject'] = "[자람] " . $articleInfo['bid'] . "에 새 글이 등록되었습니다.";
        $mail_info['message'] = "url : <a href=\"".$url."\" target=\"_blank\">"
                                .$url."</a><br>\n"
                                ."게시판 : " . $articleInfo['bid'] . "<br>\n"
                                ."작성자 : " . $articleInfo['author'] . "<br>\n"
                                ."제목 : <a href=\"" . $url . "\" target=\"_blank\">". $articleInfo['subject'] . "</a><br>\n"
                                ."작성일자 : " . date("Y-m-d H:i:s", $articleInfo['date']);

        SEND_HTML_MAIL($mail_info);
    } // end of if
}

function input_load_result($timestemp, $page, $referer, $ip, $second, $caching, $uid, $pid, $bid)
{
    if (STATUS == "product") {
		if ($uid == 0) $uid = 1500;
		$query = "INSERT INTO jaram_page_load_time (id,uid,pid,bid,timestemp,page,referer_page,ip,second,cached) VALUES ('','$uid','$pid','$bid','$timestemp','$page','$referer','$ip','$second','$caching');";
		dbconn();
		mysql_query($query);
		dbclose();
	} else {
		$GLOBALS['logger']->debug(sprintf("Page : %s \nProcess time : %s sec \nPid(-bid) : %s - %s", $page, $second, $pid, $bid));
	}
}

function login_check() {
    if (!is_login()) {
        p_redirect(WEB_ABS_PATH."/member/logout.php?error=".urlencode("권한이 없습니다")."&url=".urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

//데이터보기용
function show($data) {
    echo "<pre>\n";
    if (is_array($data)) {
        var_dump($data);
    } else {
        echo $data;
    }
    echo "</pre>";
}

// 그룹이름과 gid를 select형식으로 출력
// 그룹번호의 시작과 끝을 주면 그 구간의 그룹을 출력한다.
function show_groups($name, $start = 0, $end = 10000)
{
    echo get_select(get_group_array($start, $end), $name, "gid", "group_name");
}


/*
 * 사용권한을 체크하고 그에 따른 행동을 하는 함수
 */

function set_auth($flag) {
    if(!chk_auth($flag)) {
        $msg = "사용권한이 없습니다.";
        echo("<script type=\"text/javascript\" language=\"JavaScript\">window.alert(\"$msg\");history.go(-1);</script>");
        include (INCLUDE_PATH."/footer.inc.php");
        exit();
    }
}


// 사용예
/*
$mailinfo[from_name] = "정보통신원";
$mailinfo[from_address] = "정보통신원";
$mailinfo[to_name] = "박민우";
$mailinfo[to_address] = "zminuz@msn.com";
$mailinfo[subject] = "정보통신원 - IP 발급이 이루어졌습니다";
$mailinfo[message] = $to_name. "님 안녕하십니까?";
if(SEND_HTML_MAIL($mailinfo))
{
    echo "성공적으로 메일을 발송하였습니다.";
}
else
{
    echo "메일 발송을 실패하였습니다.";
}
*/

function SEND_HTML_MAIL($mail_info)
{
    $from_name = $mail_info[from_name]; 
    $from_address = $mail_info[from_address]; 

    $to_name = $mail_info[to_name]; 
    //$to_address = $mail_info[to_name]." <".$mail_info[to_address].">"; 
    $to_address = $mail_info[to_address]; 

    $message = $mail_info[message]; 
    $subject = $mail_info[subject]; 

    $headers .= "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=EUC-KR\r\n"; 
    $headers .= "From: ".$from_name." <".$from_address.">\r\n"; 
    $headers .= "Reply-To: ".$from_name." <".$from_address.">\r\n"; 
    $headers .= "X-Mailer: PHP / ".phpversion()."\r\n";

    //return @mail($to_address, $subject, $message, $headers); 
}

function add_spam_client($ip)
{
    // 
}

function is_spam_client()
{
	$now_ip=getenv("REMOTE_ADDR");
	$query = "SELECT * FROM jaram_spam_list WHERE ip='$now_ip'";
	dbconn();
	$result = mysql_query($query);
	if(@mysql_num_rows($result) > 0)
		return true;
	return false;
}

function isDev() {
	return "dev" == STATUS;
}

function isProduct() {
	return !isDev();
}
