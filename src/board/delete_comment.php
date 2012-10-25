<?php
require_once "include/setup.php";
require_once "include/info.php";

$smarty = new JaramSmarty();
$smarty->caching=false;

include "include/check.php";
require_once INCLUDE_PATH."/linker.inc.php";

$dbComment=new DB_mySql;

// 처음 삭제 메뉴 (혹은 버튼)을 선택할 경우만 해당
if ($HTTP_GET_VARS[id])
{
	$id=$HTTP_GET_VARS[id];
	$superid=$HTTP_GET_VARS[superid];
	$pageID=$HTTP_GET_VARS[pageID];
	$dbComment->query("select usrid, password from $M_TABLE_RE where id='$id'");
	$commentData=$dbComment->fetch_object();
}

if ( (is_login() && ($SESSION_USER_ID==$commentData->usrid) || chk_auth("auth_delete")))
{
	$dbComment->query("delete from $M_TABLE_RE where id='$id'");
    p_redirect($_SERVER['HTTP_REFERER']);
	exit;
}
else if ($passwd=$HTTP_POST_VARS[passwd])
{
	$id=$HTTP_POST_VARS[id];
	$superid=$HTTP_POST_VARS[superid];
	$dbComment->query("select id from $M_TABLE_RE where id='$id' and password=password('$passwd')");

	if ($dbComment->number())
	{
		$dbComment->query("delete from {$M_TABLE_RE} where id='$id'");

		if($USING_DIRECT_COMMENT=="true")
		{
			$_crc32=crc32($tableID."/index.tpl");
			$filename = $smarty->cache_dir.$tableID."/list/".$pageID."/no/0/%%".substr($_crc32,0,3)."/%%".$_crc32."/index.tpl.php";
			// PM 파싱 and counting 함수
			pare_chage_number($filename,"PM",$superid,-1);
		}
        p_redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
	else
	{
		$smarty->assign("tableID",$tableID);
		$smarty->assign("recordID",$id);
		$smarty->assign("superID",$superid);
		$smarty->assign("pageID",$pageID);
		// typeID : 1, 코멘트 삭제 관련
		$smarty->assign("typeID","1");
		$smarty->display("board/{$_TEMPLATE}/password.tpl");
	}
}
else if ($commentData->password)
{
	$smarty->assign("tableID",$tableID);
	$smarty->assign("recordID",$HTTP_GET_VARS[id]);
	$smarty->assign("superID",$HTTP_GET_VARS[superid]);
	$smarty->assign("pageID",$HTTP_GET_VARS[pageID]);
	// typeID : 1, 코멘트 삭제 관련
	$smarty->assign("typeID","1");
	$smarty->display("board/{$_TEMPLATE}/password.tpl");
}
