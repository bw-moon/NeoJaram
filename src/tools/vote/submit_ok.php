<?php
/*************************************
 *			vote_ok.php							*
 *			@decription : vote save			*
 *			@author : serue						*
 *************************************/

############## header ################
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");


include "./lib/admin_conf.php";	// admin config include


// database connect
dbconn();

############## main ################

$user_ip = $REMOTE_ADDR;
$signdate = time("Y-m-d");

switch($action) {

	case 'vote' :
			
			if ( !$_SESSION['jaram_user_uid'] ) { show_error_msg("권한이 없습니다!", "back"); }

//			$result = mysql_query ("SELECT * FROM jaram_vote where vote_start=);
//			$check = mysql_fetch_array($result);

			$table = "jaram_vote_result";

			// 투표했는지 안했는지 check
			$check_query = "SELECT * FROM $table WHERE vote_id='".$_GET['vote_id']."' and user_uid='".$_SESSION['jaram_user_uid']."' ORDER BY result_id";
			$check_result = mysql_query($check_query);

			if ( @mysql_num_rows($check_result) > 0) {
				show_error_msg("이미 투표하셨습니다!", "back");
				exit;
			}

			$table = "jaram_vote_option";
			
			$query = "SELECT option_result FROM $table WHERE vote_option_id='".$_POST['vote_option_id']."' ORDER BY vote_option_id";
			$result = mysql_query($query);

			$var = mysql_fetch_array($result);
			
			$option_result = $var[option_result]+1;

			$query_up = "update $table set option_result=option_result + 1 where vote_option_id='".$_POST['vote_option_id']."' and vote_id='".$_GET["vote_id"]."'";
			$result_up = mysql_query($query_up);
			

			$table = "jaram_vote_result";
			$query_insert = "insert into $table (vote_id, vote_option_id, user_uid, user_ip) values('".$_GET['vote_id']."','".$_POST['vote_option_id']."','".$_SESSION['jaram_user_uid']."','".$user_ip."')";

			$result_insert = mysql_query($query_insert);


			if ( $result && $result_insert && $result_up ) {
				echo "<script>window.alert('투표에 참여해주셔서 감사합니다!');</script>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=survey_view.php?vote_id=".$_GET['vote_id']."'&amp;page=".$_GET['page']."&amp;article_num=".$_GET['article_num']."\">";
				exit;

			} else {
				show_error_msg("데이터베이스 오류가 발생했습니다", "back");
				exit;
			}
			break;

	case 'front_vote' :

//			if ( !$_SESSION['jaram_user_uid'] ) { show_error_msg("권한이 없습니다!", "back"); }

			$table = "jaram_vote_result";

			// 투표했는지 안했는지 check
			$check_query = "SELECT * FROM $table WHERE vote_id='".$_GET['vote_id']."' and user_uid='".$_SESSION['jaram_user_uid']."' ORDER BY result_id";
			$check_result = mysql_query($check_query);

			if ( @mysql_num_rows($check_result) > 0) {
				show_error_msg("이미 투표하셨습니다!", "back");
				exit;
			}

			$table = "jaram_vote_option";
			
			$query = "SELECT option_result FROM $table WHERE vote_option_id='".$_GET['vote_option_id']."' ORDER BY vote_option_id";
			$result = mysql_query($query);

			$var = mysql_fetch_array($result);
			
			$option_result = $var[option_result]+1;

			$query_up = "update $table set option_result=option_result + 1 where vote_option_id='".$_GET['vote_option_id']."' and vote_id='".$_GET["vote_id"]."'";
			$result_up = mysql_query($query_up);
			

			$table = "jaram_vote_result";
			$query_insert = "insert into $table (vote_id, vote_option_id, user_uid, user_ip) values('".$_GET['vote_id']."','".$_GET['vote_option_id']."','".$_SESSION['jaram_user_uid']."','".$user_ip."')";

			$result_insert = mysql_query($query_insert);

			if ( $result && $result_insert && $result_up ){
				echo "<script>window.alert('투표에 참여해주셔서 감사합니다!');</script>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=./index.php?page=".$_GET['page']."\">";
				exit;

			} else {
				show_error_msg("데이터베이스 오류가 발생했습니다", "back");
				exit;
			}
			break;

	case 'comment' :

			if ( !$_SESSION['jaram_user_uid'] ) { show_error_msg("권한이 없습니다!", "back"); }

			if (empty($_POST['comment'])) {
				show_error_msg("코멘트 내용이 없습니다.", "back");
				exit;
			}

			$table="jaram_vote_comment";

			$user_uid = addslashes(htmlspecialchars($_POST['user']));
			$comment = addslashes(htmlspecialchars($_POST['comment']));
			
			$write_query="insert into $table (vote_id, comment, user_uid, user_ip, signdate) values('".$_POST['vote_id']."','".$comment."','".$user_uid."','".$user_ip."','".$signdate."')";

			$write_result = mysql_query($write_query);

			if ( $write_result ){
				echo "<meta http-equiv=\"refresh\" content=\"0;url=survey_view.php?vote_id=".$_POST['vote_id']."&amp;page=".$_POST['page']."&amp;article_num=".$_POST['article_num']."\">";

			} else {

			}
			break;

	case 'modify' :

			if ( !$_SESSION['jaram_user_uid'] ) { show_error_msg("권한이 없습니다!", "back"); }

			$topic_text = addslashes(htmlspecialchars($_POST['topic_text']));
			$vote_start = strtotime($_POST['vote_start']);
			$vote_limit = strtotime($_POST['vote_limit']);

			$topic_comment = addslashes(htmlspecialchars($_POST['topic_comment']));

			$vote_query = "UPDATE jaram_vote SET topic_text='$topic_text', vote_start='$vote_start', vote_limit='$vote_limit', topic_comment='$topic_comment' WHERE vote_id='".$_GET["vote_id"]."'";
			$vote_result = mysql_query($vote_query);

			for ($i = 1; $i < $_POST["vote_option_count"]; $i++) {
				$option_id = $_POST["option_id".$i];
				if (trim($_POST["option_text".$i]) == "") {
					$vote_option_query = "DELETE FROM jaram_vote_option WHERE vote_option_id='$option_id'";
				} else {
					$vote_option_query = "UPDATE jaram_vote_option SET option_text='".addslashes(htmlspecialchars(trim($_POST["option_text".$i])))."' WHERE vote_option_id='$option_id'";
				}

				$vote_option_restlt = mysql_query($vote_option_query);
			}

			if ( $vote_result ){
				echo "<script>window.alert('수정되었습니다!');</script>";
				echo "<meta http-equiv='refresh' content='0;url=survey_view.php?page=".$_GET['page']."&amp;vote_id=".$_GET['vote_id']."&amp;article_num=".$_GET['article_num']."'>";
			} else {
				show_error_msg("데이터베이스 오류입니다!", "back");
				exit;
			}

			break;

	case 'create' :

			if ( !$_SESSION['jaram_user_uid'] ) { show_error_msg("권한이 없습니다!", "back"); }
			
			$query = "insert into jaram_vote (topic_text, topic_comment, vote_start, vote_limit, user_uid, is_open) values('".$_POST['topic_text']."','".$_POST['topic_comment']."','".$_POST['vote_start']."','".$_POST['vote_limit']."', '".$_SESSION['jaram_user_uid']."', '".$_POST['is_open']."')";
			$result = mysql_query($query);

			$query = "select vote_id from jaram_vote where topic_comment='".$_POST['topic_comment']."'";
			$vote_result = mysql_query($query);

			$vote_id = mysql_insert_id();

			for ( $i = 0 ; $i < count($_POST['option_text']) - 1 ; $i++ ) {
				$query = "insert into jaram_vote_option (vote_id, option_text) values ( '".$vote_id."','".$_POST[option_text][$i]."')";
				$option_result = mysql_query($query);
			}
	
			if ( $result && $vote_result && $option_result ){
				echo "<script>window.alert('생성되었습니다!');</script>";
				echo "<meta http-equiv='refresh' content='0;url=./index.php'>";
			} else {
				show_error_msg("데이터베이스 오류입니다!", "back");
				exit;
			}

			break;

}
############# footer #################

include INCLUDE_PATH."/footer.inc.php";
?>