<?php
/*
 * 일정관리 프로그램 using cache
 * 일정 추가 부분
 * by 18th 문병원 
 */

include_once(realpath('../../../lib/includes')."/header.inc.php");
include_once ("libarary.schedule.php");

chk_auth("auth_post");
	
dbconn();

$tpl = new JaramSmarty();
$group = new JaramGroup();

if($_GET['mode'] == "newplan" && strlen($_GET['date']) == 8 || $_POST['mode'] == "newplan")
{
	if(strlen($_POST['subject']) < 2 || strlen($_POST['text']) < 2) {
        $tpl->assign('gid', 1002);
        $tpl->assign('groups', $group->getGroupOptions());
        $tpl->display('tools/scheduler_form.tpl');
    }
	else if($_POST['submode'] == "seminar")
	{
		$mdate = time();
		$period = substr($_POST['date'], 0, 6);
		$yy = substr($_POST['date'], 0, 4);
		$mm = substr($_POST['date'], 4, 2);
		
		if(empty($_POST['dday']))
				$_POST['dday'] = 0;

		if(empty($_POST['mailing']))
			$_POST['mailing'] = 0;

/*
		$query_sid = "SELECT seminar_id FROM jaram_seminar ORDER BY seminar_id DESC LIMIT 1;";
		$result_sid = mysql_query($query_sid);

		$row = mysql_fetch_array($result_sid);

		$seminar_id = $row['seminar_id'] + 1;
*/
		
		$query_seminar = "INSERT INTO jaram_seminar VALUES ('', '$_POST[topic]', '$_POST[desc]', '','','', '$_POST[seminar_group]');";
		mysql_query($query_seminar) or die("<b>Invalid query</b>: " . mysql_error());
		$seminar_id = mysql_insert_id();

		$query_schedule = "INSERT INTO jaram_schedule VALUES ('', '$_SESSION[jaram_user_uid]', '$_POST[subject]', '$_POST[text]', '$_POST[date]', '$period', '$mdate', '$_POST[dday]', '$_POST[mailing]', '$_POST[group]', '$seminar_id');";

		//echo $query_seminar."<br>".$query_schedule;
		mysql_query($query_schedule) or die("<b>Invalid query</b>: " . mysql_error());
		

		echo("정상적으로 내용이 입력되었습니다");
		p_redirect(WEB_ABS_PATH."/tools/scheduler/?yy=$yy&amp;mm=$mm");
	}

	else
	{
		
		if($_POST['seminar'] == "1")
		{
			// 일정 입력시 세미나부분 체크되어 있으면, 세미나 정보도 입력
			include "seminar_input.php";

		} else {

			$mdate = time();
			$period = substr($_POST['date'], 0, 6);
			$yy = substr($_POST['date'], 0, 4);
			$mm = substr($_POST['date'], 4, 2);

			if(empty($_POST['dday']))
				$_POST['dday'] = 0;

			if(empty($_POST['mailing']))
				$_POST['mailing'] = 0;
				
			$query = "INSERT INTO jaram_schedule VALUES ('', '$_SESSION[jaram_user_uid]', '$_POST[subject]', '$_POST[text]', '$_POST[date]', '$period', '$mdate', '$_POST[dday]', '$_POST[mailling]', '$_POST[group]', '0');";
			//echo $query."<br />";
			mysql_query($query) or die("<b>Invalid query</b>: " . mysql_error());
			
			echo("정상적으로 내용이 입력되었습니다");
			p_redirect("/tools/scheduler/?yy=$yy&amp;mm=$mm");
		}
		
	}

}

elseif($_GET['mode'] == "modplan" || $_POST['mode'] == "modplan")
{
}

elseif($_GET['mode'] == "delplan" || $_POST['mode'] == "delplan")
{
	if ($_GET['answer'] == "yes")
	{
		// 삭제 가능 권한 체크 - 삭제가능자 : 작성자,.. 생각해봐야겠다 by 문병원
		$sql = "SELECT uid,seminar_id FROM jaram_schedule where schedule_id = ".$_GET['id'];
		$result = mysql_query($query);
		$uid=mysql_result($result,0,0);
		$seminar_id=mysql_result($result,0,1);

		if($_SESSION[jaram_user_uid]==$uid || chk_auth("auth_delete"))
		{
			$sql="DELETE FROM `jaram_schedule` WHERE `schedule_id`=".$_GET['id'];
			mysql_query($sql) or die("<b>Invalid query</b>: " . mysql_error());
			p_redirect("/tools/scheduler");
		}
		else
		{
			show_error_msg("삭제 권한이 없습니다.", "back", "", "");
		}
	}
	else if ($_GET['answer'] == "no")
	{
		p_redirect(WEB_ABS_PATH."/tools/scheduler");
	}
	else
	{
		show_confirm_msg("일정을 삭제하고자 합니다. 허락하시겠습니까?\n세미나 일정의 경우 세미나도 삭제 됩니다.(아직 미적용)");
	}
}

elseif($_GET['mode'] == "plan_modify" || $_POST['mode'] == "plan_modify")
{
}

elseif($_GET['mode'] == "plan_delete" || $_POST['mode'] == "plan_delete")
{
}

else
{
	show_error_msg("잘못된 변수입니다", "back", "", "");
}

include_once INCLUDE_PATH."/footer.inc.php";
