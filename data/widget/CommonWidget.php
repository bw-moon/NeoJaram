<?php
require_once 'iWidget.php';

abstract class CommonWidget implements iWidget {
	public $widget_name;
	public $widget_desc;
	public $widget_icon;
	public $widget_ver;
	public $widget_nickname;
	public $widget_pref;
	public $widget_content;
    public $widget_user_id;
    public $logger;
	public $dbo;

    public function __construct($prefs = array()) {
        $this->dbo = ZendDB::getDBO();
        $this->logger = JaramLogger::getLogger();

        // TODO: widget_user_id가 있을 경우 사용자를 확인하는 과정을 넣자.


        if (!is_array($this->widget_pref)) { $this->widget_pref = array();}

        $this->widget_pref = array_merge($this->widget_pref,
             array (
                "widget_name"=>array("label"=>"제목", "value"=>$this->widget_name),
                "widget_color"=>array("label"=>"색상", "type"=>"color", "item"=>array("gray", "yellow", "blue", "red"))
             )
        );

        if (is_array($prefs)) {
            foreach ($prefs as $pref_key => $pref_value) {
                if ($pref_key && $pref_value) {
                    $this->$pref_key = $pref_value;
                }
            }
        }
    }

    function getContent() {
        return "";
    }

    public function getTriggerScript() {

    }

    function checkValidUser($widget_user_id, $user_id) {
        return $this->dbo->fetchOne("SELECT COUNT(*) FROM jaram_widget_user WHERE uid=:uid AND widget_user_id=:id", array('uid'=>$user_id, 'id'=>$widget_user_id));
    }
}
?>
