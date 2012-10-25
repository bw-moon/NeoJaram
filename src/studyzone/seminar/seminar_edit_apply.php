<?php
include_once (realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");

$seminar = new JaramSeminar($_POST['seminar_id']);

if (!is_admin() && !in_array($seminar->getSeminarGroupID(), get_gids($_SESSION['jaram_user_uid']))) {
	show_error_msg("관리자와 세미나 강연자만 수정할 수 있습니다");
}

if ($seminar->uploadSeminarFile($_REQUEST, $_FILES) && $seminar->updateSeminar($_REQUEST)) {
	echo "정상적으로 수정되었습니다.";
	flush();
	p_redirect(WEB_ABS_PATH."/studyzone/seminar/seminar_view.php?seminar_id=".$_POST['seminar_id']);
}

include (INCLUDE_PATH."/footer.inc.php");
?>