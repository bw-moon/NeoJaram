<?php
/* file : seminar_view.php
 * Study Zone > Seminar 
 * by 18th 문병원 
 */

include_once realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php";

// 본분내용

$query = "SELECT a.*, c.group_name, b.schedule_start
FROM jaram_seminar AS a LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id
   LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
WHERE a.seminar_id = :id";

$dbo  = ZendDB::getDBO();
$data = $dbo->fetchRow($query, array('id'=>$_GET['seminar_id']));

$date = get_each_date($data['schedule_start']);

if (!$data['seminar_desc'])
{
	$data['seminar_desc'] = "아직, 내용이 입력되지 않았습니다.<br/>";
}


// 파일 업로드
$sql = "SELECT * FROM jaram_seminar_file WHERE seminar_id = :id";

$files = $dbo->fetchAll($sql, array('id'=>$_GET['seminar_id']));

foreach ($files as $file) {
    if ($file['file_flag'] == "original") {
		$uploadfile1 = "원본문서 : ";
		$uploadfile1 .= icon($file['filename']);
		$uploadfile1 .= " <a href=\"".WEB_ABS_PATH.urlencode("/studyzone/seminar/data/".$file['file_flag2']."/".$file['filename'])."\" target=\"_blank\">".$file['filename']."</a>";
        $uploadfile[] = $uploadfile1;
	}
	if ($file['file_flag'] == "printable") {
		$uploadfile2 = "인쇄용문서 : ";
		$uploadfile2 .= icon($file['filename']);
	    $uploadfile2 .= " <a href=\"".WEB_ABS_PATH.urlencode("/studyzone/seminar/data/".$file['file_flag2']."/".$file['filename'])."\" target=\"_blank\">".$file['filename']."</a>";
        $uploadfile[] = $uploadfile2;
	}
}

if (is_login()) {
	$uploadfile = implode("<br/>", $uploadfile);
} else {
	$uploadfile = $uploadfile2;
}

// 글을 읽을때 입력
if (is_login())
{	
	$query_readers = "SELECT COUNT(*) FROM jaram_seminar_reader AS a LEFT JOIN jaram_groups AS b ON a.user_id = b.gid WHERE a.seminar_id=:seminar_id AND a.user_id=:uid";
    $already_read = $dbo->fetchOne($query_readers, array('seminar_id'=>$data['seminar_id'], 'uid'=>$_SESSION['jaram_user_uid']));

	if (!$already_read)
	{
        $row = array('seminar_id'=>$data['seminar_id'], 'user_id'=>$_SESSION['jaram_user_uid'], 'seminar_reader_date'=>time());
        $dbo->insert('jaram_seminar_reader', $row);
	}
}

// 읽은 사람들
$query_readers = "SELECT b.gid,b.group_name FROM jaram_seminar_reader AS a LEFT JOIN jaram_groups AS b ON a.user_id = b.gid WHERE a.seminar_id = :seminar_id";
$readers = $dbo->fetchAll($query_readers, array('seminar_id'=>$data['seminar_id']));


if($readers)
{
	$count = 0;
	$article_readers = "이글을 읽은 사람들(".count($readers)."명) : ";
    foreach ($readers as $reader)
	{
		$reader_token[] = "<a href=\"".WEB_ABS_PATH."/jaram/memberinfo/?gid=".$reader['gid']."\">".$reader['group_name']."</a>";
    }
    $article_readers .= implode(", ", $reader_token);
}


// 코멘트
$query = "SELECT a.comment_id, b.uid, a.name, a.text, a.reg_date, b.user_name FROM jaram_seminar_comment AS a LEFT JOIN jaram_users AS b ON a.user_id = b.uid WHERE a.seminar_id =:seminar_id";
$comments = $dbo->fetchAll($query, array('seminar_id'=>$data['seminar_id']));


$smarty = new JaramSmarty();
$smarty->assign('data', $data);
$smarty->assign('uploadfile', $uploadfile);
$smarty->assign('article_readers', $article_readers);
$smarty->assign('comments', $comments);
$smarty->display('studyzone/seminar_view.tpl');

include_once (INCLUDE_PATH."/footer.inc.php");
?>