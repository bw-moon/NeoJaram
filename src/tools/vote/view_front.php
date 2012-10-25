<?php
/*************************************
 *			view_front.php						*
 *			@decription : survey view 		*
 *			@author : serue						*
 *************************************/

dbconn();

############### main ################

// vote 테이블에서 현재 진행중인 투표의 갯수를 알아낸다
$time_stamp = time();

if (is_login())
{
	$query = "SELECT vote_id,topic_text FROM jaram_vote WHERE vote_start <= '".$time_stamp."' AND vote_limit >= '".$time_stamp."' ORDER BY vote_id DESC;";
} else {
	$query = "SELECT vote_id,topic_text FROM jaram_vote WHERE vote_start <= '".$time_stamp."' AND vote_limit >= '".$time_stamp."' AND is_open = 'on' ORDER BY vote_id DESC;";
}

$result_present = mysql_query($query);

// 총 투표수
$total = 0;	

// 현재 진행중인 총 투표의 수
$total_present = @mysql_num_rows($result_present);

if (@mysql_num_rows($result_present) > 0) {
	// 모든 자료 검색 후 진행중인지 아닌지 확인
	while ( $data = mysql_fetch_array($result_present) ) {

		echo "title : ".$data['topic_text']."<br/>";

		if (isset($_SESSION['jaram_user_uid'])) {
			// 투표했는지 안했는지 check
			$check_query = "SELECT * FROM jaram_vote_result WHERE vote_id='".$data['vote_id']."' and user_uid='".$_SESSION['jaram_user_uid']."';";
		} else {
			$check_query = "SELECT * FROM jaram_vote_result WHERE vote_id='".$data['vote_id']."' AND user_ip !='".getIPADDR()."';";
		}

		$check_result = mysql_query($check_query);

		if ( @mysql_num_rows($check_result) > 0) {
			// 투표결과를 보여준다.
			$result = mysql_query("SELECT option_text, option_result FROM jaram_vote_option WHERE vote_id = '".$data['vote_id']."' ORDER BY vote_option_id ASC;");

			while ( $var = mysql_fetch_array ($result) ) {
				$total += (int)$var['option_result'];
			}

			while ($var = mysql_fetch_array ($result)) {
				$option_width = 100 * ($var['option_result'] / $total);
			?>					
				<?=$var['option_text']?><br/> <img src="/img/vote_pic.gif" height="10" width="<?=$option_width?>%"/><br/>
			<?
			}

		} else {
			// 투표옵션을 보여준다.
			$qry_present = "SELECT a.vote_id, a.topic_text, a.is_open, b.vote_option_id, b.option_text FROM jaram_vote AS a RIGHT JOIN jaram_vote_option AS b ON a.vote_id=b.vote_id WHERE a.vote_id = '".$data['vote_id']."' ;";
			$result = mysql_query($qry_present);

			while ($var = mysql_fetch_array ( $result ) ) {
				echo "<a href=\"/tools/vote/submit_ok.php?action=front_vote&amp;page=1&amp;vote_id=".$data['vote_id']."&amp;vote_option_id=".$var['vote_option_id']."\">".$var['option_text']."</a><br/>";
			}
		}			
	}
	

} else {
	echo("진행중인 투표가 없습니다");
}

?>