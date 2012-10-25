<?php
require "include/setup.php";
include "include/info.php";

$smarty = new JaramSmarty();
$smarty->caching=false;

include "include/check.php";

$dbNote=new DB_mySql;

function MoveNote($id,$fromBoard,$toBoard)
{
	$dbNote=new DB_mySql;
	global $USING_FILE, $USING_COMMENT,$UPLOAD_DIR, $USING_PREVIEW_IMG;

	$M_TABLE1="mboard_".$fromBoard;
	$M_TABLE_RE1="mboard_".$fromBoard."_re"; 
	$M_TABLE_FILE1="mboard_".$fromBoard."_file";

	$M_TABLE2="mboard_".$toBoard;
	$M_TABLE_RE2="mboard_".$toBoard."_re"; 
	$M_TABLE_FILE2="mboard_".$toBoard."_file";

	// 게시물 이동
	$dbNote->query("select * from $M_TABLE1 where id='$id'");
	$noteData=$dbNote->fetch_array();

	$replyDepth='0';
	$replyLocate=$dbNote->next_id($M_TABLE2);
	$replySortno='0';
	
	$sql="INSERT INTO $M_TABLE2 VALUES ('', '$noteData[title]', '$noteData[kind]', '$noteData[name]', '$noteData[usrid]', '$noteData[password]', '$noteData[email]', '$noteData[homepage]', '$noteData[note]', '$noteData[file_number]','$noteData[file_size]', '$noteData[date]','$noteData[host]', '$noteData[count]', '$replyLocate', '$replyDepth', '$replySortno','$noteData[extend1]','$noteData[extend2]')";
	//echo $sql."<br/>";
	$dbNote->query($sql);

	// 게시물 삭제
	$dbNote->query("delete from $M_TABLE1 where id='$id'");

	// 코멘트 이동
	if ($USING_COMMENT=="true")
	{
		$dbNote->query("select * from $M_TABLE_RE1 where subid='$id'");
		while($commentData=@$dbNote->fetch_array())
		{
			$sql="INSERT INTO $M_TABLE_RE2 VALUES ('', '$commentData[name]', '$commentData[usrid]', '$commentData[note]', '$commentData[password]', '$commentData[date]', '$replyLocate')";
			//echo $sql."<br>";
			$dbNote->query($sql);
		}
		$dbNote->query("delete from $M_TABLE_RE1 where subid='$id'");
	}

	// 업로드 파일 이동
	if ($USING_FILE=="true")
	{
		$dbFile=new DB_mySql;
		$dbFile->query("select * from $M_TABLE_FILE1 where sub_id=".$id);
		$numberFile=$dbFile->number();
		for ($tempCount=0;$tempCount<$numberFile;$tempCount++)
		{
			$dbFile->seek($tempCount);
			$fileData=$dbFile->fetch_array();
			rename($UPLOAD_DIR."/".$fromBoard."/".$fileData[file_link],$UPLOAD_DIR."/".$toBoard."/".$fileData[file_link]);
			if($USING_PREVIEW_IMG=="true")
				@unlink($UPLOAD_DIR."/".$fromBoard."/".$fileData[file_link]."_resize");

			$sql="INSERT INTO $M_TABLE_FILE2 VALUES ('', '$fileData[file_name]', '$fileData[file_link]', '$fileData[file_size]', '$replyLocate', '$fileData[file_date]', '$fileData[file_count]')";
			//echo $sql."<br>";
			$dbNote->query($sql);
		}
		$dbFile->close();
		unset($dbFile);

		// 파일 DB 삭제
		$dbNote->query("delete from $M_TABLE_FILE1 where sub_id='$id'");
	}

	$dbNote->close();
}


// 처음 삭제 메뉴 (혹은 버튼)을 선택할 경우만 해당
$id=$_GET[id];
$dbNote->query("select usrid, password from $M_TABLE where id='$id'");
$noteData=$dbNote->fetch_object();

if ( ($SESSION_USER_ID && $SESSION_USER_ID==$noteData->usrid) || chk_auth("auth_delete"))
{
	//echo $id."<br>".$_GET[from]."<br>".$_GET[to]."<br>";
	// 게시물 삭제 함수
	MoveNote($id, $_GET[from], $_GET[to]);
	$smarty->clear_cache(null,$_GET[from]."|list");
	$smarty->clear_cache(null,$_GET[to]."|list");
	echo "
		<script language=\"JavaScript\">
		parent.location=\"bbs.php?tableID=$tableID&pageID=$_GET[superid]\";
		</script>
			";
	exit;
}
else
{
	echo "권한이 없습니다.<br/>";
}


?>