<?
require_once "include/setup.php";
require_once "include/info.php";

$smarty = new JaramSmarty();
$smarty->caching=false;

require_once "include/check.php";
require_once INCLUDE_PATH."/linker.inc.php";

$dbNote=new DB_mySql;

// 처음 삭제 메뉴 (혹은 버튼)을 선택할 경우만 해당
if ($HTTP_GET_VARS[id])
{
	$id=$_GET['id'];
	$superid=$_GET['superid'];
	$dbNote->query("select usrid, password from $M_TABLE where id='$id'");
	$noteData=$dbNote->fetch_object();
}

if ($noteData->usrid && !$noteData->password)
{
	if ( ($SESSION_USER_ID && $SESSION_USER_ID==$noteData->usrid) || chk_auth("auth_edit"))
	{
		echo "
			<script language=\"JavaScript\">
			parent.location=\"write.php?tableID=$tableID&pageID=$superid&modID=$id\";
			</script>
				";
		exit;
	}
}
else if ($passwd=$_POST['passwd'] || chk_auth("auth_edit"))
{
	$id=$HTTP_POST_VARS[id];
	$superid=$HTTP_POST_VARS[superid];
	$dbNote->query("select id from $M_TABLE where id='$id' and password=password('$passwd')");

	if ($dbNote->number())
	{
		echo "
			<form name=\"modify\" method=\"post\" target=\"_parent\"  action=\"write.php?tableID=$tableID&pageID=$superid&modID=$id\">
			<input type=\"hidden\" name=\"passwd\" value=\"$passwd\" />
			</form>
			<script language=\"JavaScript\">
			modify.submit();
			</script>
				";
		exit;
	}
	else
	{
		$smarty->assign("tableID",$tableID);
		$smarty->assign("recordID",$id);
		$smarty->assign("superID",$superid);
		// typeID : 3, 수정 관련
		$smarty->assign("typeID","3");
		$smarty->display("board/{$_TEMPLATE}/password.tpl");
	}
}
else if ($noteData->password)
{
	$smarty->assign("tableID",$tableID);
	$smarty->assign("recordID",$HTTP_GET_VARS[id]);
	$smarty->assign("superID",$HTTP_GET_VARS[superid]);
	// typeID : 3, 수정 관련
	$smarty->assign("typeID","3");
	$smarty->display("board/{$_TEMPLATE}/password.tpl");
}
?>