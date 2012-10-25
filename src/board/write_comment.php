<?php
ob_start();
// 코멘트 쓰기시 자동 로그인 추가
// 2006.10.09 by zeru
include_once(realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");
include_once(INCLUDE_PATH."/auto_login.inc.php");

include_once "include/setup.php";
include_once "include/info.php";

$GLOBALS['logger']->debug($_POST);

$smarty = new JaramSmarty(); 

include_once "include/check.php";

if (!$_POST["s_id"])
{
	echo("error : wrong article id"); 
	exit;
}
// 로그인된 경우 생각
if (!$_POST["s_name"] && !is_login())
{
	echo("error : name field is empty"); 
	exit;
}
if (!$_POST["s_note"])
{
	echo("error : s_note field is empty"); 
	exit;
}

$dbo = ZendDB::getDBO();
$row = array('name' => stripslashes($_POST['s_name']),
				'usrid'=>$_SESSION['jaram_user_uid'],
				'note'=>stripslashes($_POST['s_note']),
				'password'=>md5($_POST['s_pass']),
				'date'=>date("c"),
				'subid'=>$_POST['s_id']
			);

$dbo->insert($M_TABLE_RE, $row);
$last_comment_id = $dbo->lastInsertId();
$logger->debug("입력된 코멘트 : {$last_comment_id}");

$comment = $dbo->fetchRow("select a.*, c.user_name, c.user_having_image2 from jaram_board_comment as a LEFT JOIN {$USER_TABLE} as c ON a.usrid=c.uid  where id=:id",array('id'=>$last_comment_id));
$article_date = $dbo->fetchOne("SELECT date FROM jaram_board WHERE id=:id", array('id'=>$comment['subid']));
$comment['time_gap']= strtotime($comment['date']) - strtotime($article_date); 

$smarty->assign("comments", array($comment));

ob_end_clean();

$smarty->display("board/{$_TEMPLATE}/view_comment.tpl");
?>