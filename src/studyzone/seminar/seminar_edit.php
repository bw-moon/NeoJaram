<?php
if ($_POST['mode'] == "modify") {
	include_once (realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");
	$seminar = new JaramSeminar($_POST['seminar_id']);

	if (!is_admin() && !in_array($seminar->getSeminarGroupID(), get_gids($_SESSION['jaram_user_uid']))) {
		show_error_msg("관리자와 세미나 강연자만 수정할 수 있습니다");
	}

	if ($seminar->uploadSeminarFile($_REQUEST, $_FILES) && $seminar->updateSeminar($_REQUEST)) {
		echo "정상적으로 수정되었습니다.";
		flush();
		p_redirect(WEB_ABS_PATH."/studyzone/seminar/seminar_view.php?seminar_id=".$_POST['seminar_id']);
		exit;
	}
}

/*
// 위키를 사용할 경우
$inline_javascript = 	"
<script language=\"JavaScript\" type=\"text/javascript\">

var Wiki = {
    time: 'hihi',
    editor: function (event)
	{
		if (event.keyCode==Event.KEY_TAB)
		{
			event.returnValue = false;
			document.selection.createRange().text = String.fromCharCode(9);
		}
	}
}
Event.observe('seminar_topics', 'onkeydown', Wiki.editor.bindAsEventListener(Wiki));
</script>";
*/

include_once (realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");
include_once ("../../editor/fckeditor/fckeditor.php") ;

$dbo = ZendDB::getDBO();

$query = "SELECT 
a.*, c.group_name, b.schedule_start, c.group_name
FROM jaram_seminar AS a 
    LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id
    LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
WHERE a.seminar_id = :seminar_id";

$seminar_data = $dbo->fetchRow($query, array('seminar_id'=>$_REQUEST['seminar_id']));

if (!$seminar_data)
{
	show_error_msg("내용이 존재하지 않습니다", "back");
}

$date = get_each_date($seminar_data['schedule_start']);

$sql = "SELECT * FROM jaram_seminar_file WHERE seminar_id = :seminar_id";
$seminar_files = $dbo->fetchAll($sql, array('seminar_id'=>$_REQUEST['seminar_id']));
$uploadfiles = array();
foreach ($seminar_files as $file) {
	if ($file['file_flag'] == "original") {
		$temp = icon($file['filename']);
		$temp .= " <a href=\"".urlencode("/studyzone/seminar/data/".$file['file_flag2']."/".$file['filename'])."\" target=\"_blank\">".$file['filename']."</a>";
        $uploadfiles['original'] = $temp;
    }
	if ($file['file_flag'] == "printable") {
		$temp = icon($file['filename']);
		$temp .= " <a href=\"".urlencode("/studyzone/seminar/data/".$file['file_flag2']."/".$file['filename'])."\" target=\"_blank\">".$file['filename']."</a>";
        $uploadfiles['printable'] = $temp;
    }
}

// 이지윅 에디터 세팅
$oFCKeditor = new FCKeditor('seminar_topics') ;
$oFCKeditor->BasePath	= "{$web_abs_path}/editor/fckeditor/";
$oFCKeditor->Height       = '400px';
$oFCKeditor->ToolbarSet = 'Basic';


if ($seminar_data['seminar_topics_type'] == 'wakka') {
    include_once 'seminar_formatter.php';
    $oFCKeditor->Value = process_wakka($seminar_data['seminar_topics']);
} else {
    $oFCKeditor->Value = $seminar_data['seminar_topics'];
}

$smarty = new JaramSmarty();
$smarty->assign("editor", $oFCKeditor->CreateHtml());
$smarty->assign("editor_type", "fck");
$smarty->assign("seminar_topics_type", "xhtml");
$smarty->assign('data', $seminar_data);
$smarty->assign('files', $uploadfiles);
$smarty->display('studyzone/seminar_form.tpl');

include_once (INCLUDE_PATH."/footer.inc.php");
?>
