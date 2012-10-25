<?php
require_once 'CommonWidget.php';

class NoteWidget extends CommonWidget {
    private $note_hash;

	public function __construct($pref = array()) {
        $this->widget_name = 'Small Note';
        $this->widget_desc = '쉽게 내용을 기록하는 메모장';
        $this->widget_created = "2007-05-15";
        $this->widget_icon = 'note';
        $this->widget_ver = '1.0';
        $this->widget_nickname = 'note';
        $this->widget_content = '';
        
        parent::__construct($pref);
	}

	public function getContent() {
        if ($this->checkValidUser($this->widget_user_id, $_SESSION['jaram_user_uid'])) {
            $content = $this->dbo->fetchOne("SELECT widget_content FROM jaram_widget_user WHERE widget_user_id=:id", array("id"=>$this->widget_user_id));
            $rv = "<p id=\"widget_note_{$this->widget_user_id}\" class=\"widget_note\">";
            $rv .= ($content) ? $content : "내용을 입력해 주세요.";
            $rv .= "</p>";
            return $rv;
        } else {
            return "<p id=\"widget_note_{$this->widget_user_id}\" class=\"widget_note\">권한이 없습니다</p>";
        }
	}

    function getTriggerScript() {
        return "new Ajax.InPlaceEditor($('widget_note_{$this->widget_user_id}'), server_url+'?action=process_widget&method=setContent&widget_user_id={$this->widget_user_id}', 
            {
                rows:5,
                paramName:'widget_note', 
                ajaxOptions: {method: 'get'} 
            }
        );";
    }

    function setContent($post_data) {
        $this->logger->debug("setContent : {$post_data['widget_note']}");
        if ($this->checkValidUser($post_data['widget_user_id'], $_SESSION['jaram_user_uid'])) {
            if (!$post_data['widget_note']) $post_data['widget_note'] = "내용을 입력해주세요.";
            $row = array('widget_content'=>stripslashes($post_data['widget_note']));
            $where = $this->dbo->quoteInto('widget_user_id=?', $post_data['widget_user_id']);
            $this->dbo->update('jaram_widget_user', $row, $where);
        }
        echo stripslashes($post_data['widget_note']);
    }
}