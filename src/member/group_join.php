<?
login_check();

if (!empty($_GET['gid']) && !empty($_SESSION['jaram_user_uid'])) {
	if ($_GET['mode'] == "leave") {
		if ($_GET['answer'] == "yes") {
			$sql = "DELETE FROM jaram_user_group WHERE uid='".$_SESSION['jaram_user_uid']."' AND gid='".$_GET['gid']."';";
			if (mysql_query($sql)) {
				show_std_msg("정상적으로 그룹에서 탈퇴되었습니다.", "std", "./?page=group_list");
			} else {
				show_error_msg("탈퇴 과정에서 문제가 발생하였습니다. 아래 쿼리를 복사하여 메일로 보내주세요.\n".$sql, "std");
			}
		} 
		else if ($_GET['answer'] == "no") {
			p_redirect("/?page=group_list");
		}
		else {
			echo "<br/>";
			$group_info = get_group_info($_GET['gid']);
			$msg = "<b>".$group_info['group_name']."</b> 그룹의 멤버에서 탈퇴하려고 합니다.\n탈퇴 이후에는 다시 가입 신청을 하고 승인이 있어야 그룹을 이용할 수 있습니다.\n\n탈퇴하시겠습니까?"; 
			show_confirm_msg($msg);
		}
	}

	else if ($_GET['mode'] == "cancel") {
		$sql = "DELETE FROM jaram_group_join_wait WHERE uid='".$_SESSION['jaram_user_uid']."' AND gid='".$_GET['gid']."';";
		if (mysql_query($sql)) {
			show_std_msg("정상적으로 그룹 신청이 취소되었습니다.", "std", "./?page=group_list");
		} else {
			show_error_msg("취소 과정에서 문제가 발생하였습니다. 아래 쿼리를 복사하여 메일로 보내주세요.\n".$sql, "std");
		}
	}

	else {

		// 이미 가입되어 있을 경우 에러
		$result = mysql_query("SELECT count(*) AS num FROM jaram_user_group WHERE gid='".$_GET['gid']."' AND uid='".$_SESSION['jaram_user_uid']."';");
		$join_count = mysql_fetch_array($result);

		// 이미 신청되어 있을 경우 에러
		$result = mysql_query("SELECT count(*) AS wait FROM jaram_group_join_wait WHERE uid='".$_SESSION['jaram_user_uid']."' AND gid='".$_GET['gid']."';");
		$wait_count = mysql_fetch_array($result);

		
		if ($join_count['num'] > 0) {
			$msg = "이미 가입되어 있는 그룹입니다.\n가입되어 있는 그룹에 대한 탈퇴는 가입목록에서 Remove 선택하셔야 합니다.";
			show_error_msg($msg, "std");
		}
		
		else if ($wait_count['wait'] > 0) {
			$msg = "이미 신청되어 있는 그룹입니다.\n신청되어 있는 그룹에 대한 취소는 대기목록에서 Cancel를 선택하셔야 합니다.";
			show_error_msg($msg, "std");
		}

		else {

			$sql = "INSERT INTO jaram_group_join_wait VALUES('', '".$_SESSION['jaram_user_uid']."', '".$_GET['gid']."');";

			if (mysql_query($sql)) {

				show_std_msg("정상적으로 그룹가입 승인을 신청하였습니다.");
				$sendmail = send_group_admin($_GET['gid'], "그룹 가입신청이 접수되었습니다.\n<a href=\"http://jaram.org/?page=group_permit\" target=\"_blank\">이곳을 클릭하면 가입 승인이 가능합니다.</a>");

				if ($sendmail) {
					echo "메일 발송되었음";
				}

			}
			else
				show_error_msg("그룹가입 신청 도중 오류가 발생했습니다.", "std");
		}
	}


} else {
	show_error_msg("그룹가입 신청 도중 오류가 발생했습니다.", "std");
}


function send_group_admin($gid, $message) {
	$gainfo = get_group_admin_info($gid);
	$ginfo = get_group_info($gid);
	$mailinfo[from_name] = "자람";
	$mailinfo[from_address] = WEBMASTER_MAIL;
	$mailinfo[to_name] = $gainfo['user_name'];
	$mailinfo[to_address] = $gainfo['user_email'];
	$mailinfo[subject] = "[자람]".$ginfo['group_name']." 그룹에 가입신청이 접수되었습니다.";
	$mailinfo[message] = $message;
	if(SEND_HTML_MAIL($mailinfo))
		return true;
	else
		return false;
}
?>