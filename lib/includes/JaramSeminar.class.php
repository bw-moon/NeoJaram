<?php
require_once('library.inc.php');

class JaramSeminar extends JaramCommon {

    function __construct ($seminar_id) {
        parent::__construct();
		$this->seminar_id = $seminar_id;
    }

	function init() {
		$this->seminar = $this->getSeminar($this->seminar_id);
	}

	function getSeminar($seminar_id) {
		$query = "SELECT a.*, c.group_name, b.schedule_start
                       FROM jaram_seminar AS a LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
                       WHERE a.seminar_id = :id";

		return $this->dbo->fetchRow($query, array('id' => $seminar_id));
	}

	function updateSeminar($postdata) {
		$row = array(
						"seminar_topic" => $postdata['seminar_topic'],
						"seminar_desc" => $postdata['seminar_desc'],
						"seminar_topics" => stripslashes($postdata['seminar_topics']),
						"seminar_topics_type" => $postdata['seminar_topics_type'],
						"seminar_benefit" => $postdata['seminar_benefit'],
						"seminar_group_name" => $postdata['seminar_group_name'],
						"seminar_group_id" => JaramGroup::getGroupIdByName($postdata['seminar_group_name'])
					);

		$where = $this->dbo->quoteInto("seminar_id=?", $postdata['seminar_id']);
		return $this->dbo->update('jaram_seminar', $row, $where);
	}

	function uploadSeminarFile($postdata, $filedata) {

		if ($filedata) {
			$seminar_time = strtotime($this->seminar["schedule_start"]);
			$DATA_DIRECTORY = date("Y_m_d_{$this->getSeminarGroupID()}", $seminar_time);


			// todo : 프로그램 루트의 data디렉토리에 생성해서 파일을 다운받는 루틴을 만들어 파일 다운 받는 것을 제어할 수 있도록 만들 것

			/* 디렉토리 생성 */
			$uploadpath = HOMEPAGE_PATH . "/studyzone/seminar/data/";
			$uploadpath.= $DATA_DIRECTORY. "/";

			if (!file_exists($uploadpath)) {
				mkdir($uploadpath, 0755);
			}

			foreach ($filedata as $key => $FILE)
			{	
				if ($key == "file_upload1") {
					$flag = "original";
				} elseif ($key = "file_upload2") {
					$flag = "printable";
				} else {
					$flag = "";
				}

				$sql = "SELECT COUNT(*) FROM jaram_seminar_file WHERE (seminar_id = '$_POST[seminar_id]' AND file_flag = '$flag');";
				$is_uploaded = $this->dbo->fetchOne($sql);
		
				if (move_uploaded_file($FILE["tmp_name"], $uploadpath . $FILE['name'])) {

					if ($is_uploaded) {
						$sql = "UPDATE jaram_seminar_file SET filename = '$FILE[name]' WHERE (seminar_id = '$_POST[seminar_id]' AND file_flag = '$flag');";
						
					} else {
						$sql = "INSERT INTO `jaram_seminar_file` ( `seminar_file_id` , `seminar_id` , `filename` , `file_desc` , `file_flag` , `file_flag2` ) ";
						$sql .= "VALUES ('', '$_POST[seminar_id]', '$FILE[name]', '', '$flag', '$DATA_DIRECTORY')";
					}
					$this->dbo->query($sql);
				}
			}
		}
		return true;
	}

	function getSeminarGroupID() {
		return $this->seminar['seminar_group_id'];
	}



   
}

?>