<?php
require_once 'CommonWidget.php';

class VoteWidget extends CommonWidget {
	public function __construct($pref = array()) {
		$this->widget_name = "Live Poll";
		$this->widget_desc = "투표 위젯";
		$this->widget_icon = "chart_bar";
		$this->widget_ver = "1.0";
		$this->widget_nickname = "vote";
		$this->widget_content = "";

        parent::__construct($pref);
	}

	public function getContent() {

		$result_html = "";
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

				$result_html .=  "title : ".$data['topic_text']."<br/>";

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
						$result_html .= "{$var['option_text']}<br/> <img src=\"img/vote_pic.gif\" height=\"10\" width=\"{$option_width}%\" alt=\"{$option_width}%\"/><br/>";
					}

				} else {
					// 투표옵션을 보여준다.
					$qry_present = "SELECT a.vote_id, a.topic_text, a.is_open, b.vote_option_id, b.option_text FROM jaram_vote AS a RIGHT JOIN jaram_vote_option AS b ON a.vote_id=b.vote_id WHERE a.vote_id = '".$data['vote_id']."' ;";
					$result = mysql_query($qry_present);

					while ($var = mysql_fetch_array ( $result ) ) {
						$result_html .=  "<a href=\"/tools/vote/submit_ok.php?action=front_vote&amp;page=1&amp;vote_id=".$data['vote_id']."&amp;vote_option_id=".$var['vote_option_id']."\">".$var['option_text']."</a><br/>";
					}
				}			
			}
		} else {
			$result_html .= "<span class=\"sub\">진행중인 투표가 없습니다.<br/>예전 투표를 표시합니다.</span><br/><br/>";
			$range = $this->dbo->fetchRow("SELECT MAX(vote_id) AS max_id, MIN(vote_id) AS min_id FROM jaram_vote");
			$rand_vote = null;
			$total = 0;
			do {
				$vote_candidate = rand($range['min_id'], $range['max_id']);
				$rand_vote = $this->dbo->fetchRow("SELECT * FROM jaram_vote WHERE vote_id=:vote_id", array('vote_id'=>$vote_candidate));
				$total = $this->dbo->fetchOne("SELECT SUM(option_result) FROM jaram_vote_option WHERE vote_id=:vote_id", array('vote_id'=>$vote_candidate));
			} while ($rand_vote == null);
			
	
			$result_html .= sprintf("<h4><a href=\"%s/tools/vote/survey_view.php?vote_id=%d\">%s</a> <span class=\"sub\">(total : {$total})</span></h4>",WEB_ABS_PATH, $rand_vote['vote_id'], $rand_vote['topic_text']);
			$result_html .= sprintf("<span class=\"sub\">%s~%s</span>", date("Ymd", $rand_vote['vote_start']), date("Ymd", $rand_vote['vote_start']));

			$result_html .= $this->showResult($rand_vote['vote_id']);
		}
		return $result_html;
	}

	private function showResult($vote_id) {
		// 투표결과를 보여준다.

		$result = $this->dbo->fetchAll("SELECT option_text, option_result FROM jaram_vote_option WHERE vote_id = :vote_id ORDER BY option_result DESC", array('vote_id'=>$vote_id));
		
		$total = $this->dbo->fetchOne("SELECT SUM(option_result) FROM jaram_vote_option WHERE vote_id=:vote_id", array('vote_id'=>$vote_id));
		$result_html = "<ul>";
		foreach ($result as $var) {
			$option_width = 100 * ($var['option_result'] / $total);
			$result_html .= "<li>{$var['option_text']}<br/> <img src=\"".WEB_ABS_PATH."/tools/vote/img/vote_pic.gif\" height=\"10\" width=\"{$option_width}%\" alt=\"{$option_width}%\"/> <span class=\"sub\">{$var['option_result']}</span></li>";
		}
		$result_html .="</ul>";
		return $result_html;
	}
}
