<?php
require_once('library.inc.php');

class JaramWidget extends JaramCommon {
    private $widget_id;
    private $widget_user_id;

    function JaramWidget($widget_id = 0) {
        $this->widget_id = $widger_id;

        parent::__construct();
    }

    function updateWidgetPref($post_data) {
        $where = $this->dbo->quoteInto('widget_user_id=?', $post_data['widget_user_id']);
        $row = array('widget_pref'=>serialize($post_data));
        return $this->dbo->update('jaram_widget_user', $row, $where);
    }

    function existsWidget($widget_name) {
        return $this->dbo->fetchOne("SELECT COUNT(*) FROM jaram_widget WHERE widget_nickname=:nickname", array('nickname'=>$widget_name));
    }

    function getWidgetInstance($uid, $status = array('allow')) {
        $widget_status = "('" . implode("','", $status) . "')";
        $result=
            $this->dbo->fetchAll("SELECT
    concat(w.widget_icon,'.gif') widget_icon, w.widget_name, w.widget_location, w.widget_content, a.widget_pref, w.widget_nickname, w.widget_desc, w.reg_date, w.modify_date, a.sort_order, a.widget_user_id, a.widget_hide, a.widget_user_id, w.widget_status
FROM
    jaram_widget_user AS a
    LEFT JOIN jaram_widget AS w 
        ON a.widget_id=w.widget_id
WHERE
    a.uid=:uid AND w.widget_status IN {$widget_status}
ORDER BY a.sort_order ASC", array('uid'=>$uid));
        return $result;
    }

    function getWidgets() {
        $widgets = $this->dbo->fetchAll("SELECT w.widget_id, CONCAT(w.widget_icon,'.gif') widget_icon, w.widget_name, w.widget_location, w.widget_content, w.widget_pref, w.widget_status, w.widget_nickname, w.widget_desc, w.reg_date, w.modify_date, u.user_name, u.uid FROM jaram_widget AS w LEFT JOIN jaram_users AS u ON w.widget_author=u.uid");

        // 5%의 확률로 위젯 파일에 있는 정보를 DB에 동기화 시킴
        if (rand(1,100) >= 5) {
            $this->syncWidgetInfo($widgets);
        }

    	return $widgets;
    }


    function syncWidgetInfo($widgets) {
        foreach ($widgets as $widget) {
            $where = $this->dbo->quoteInto("widget_id=?", $widget['widget_id']);

            $widget_obj = JaramWidget::getWidgetObj($widget['widget_nickname']);
            $row = array('widget_name' => $widget_obj->widget_name,
                'widget_desc'=>$widget_obj->widget_desc);

            $this->dbo->update('jaram_widget', $row, $where);
        }
    }

    function uploadFiles($file_data) {
        $uploaded = array();
        foreach ($file_data as $field => $file) {
            $file_path = WIDGET_PATH.DIRECTORY_SEPARATOR.$file['name'];
            if (is_uploaded_file($file['tmp_name']) && !file_exists($file_path) && move_uploaded_file($file['tmp_name'], $file_path)) {
                $uploaded[$field] = $file['name'];
            } else {
                return -1;
            }
        }
    }

    function enableWidget($widget_id) {
        $where = $this->dbo->quoteInto("widget_id=?", $widget_id);
        return $this->dbo->update("jaram_widget", array('widget_status'=>'allow'), $where);
    }

    function addWidget($post_data) {
        global $_FILES;

        $widget_token = explode(".", $_FILES['widget_file']['name']);
        $widget_name = $widget_token[0];
        $widget_type = end($widget_token);

        if ($this->existsWidget($widget_name)) {
            return -1;
        }

        $uploaded_files = $this->uploadFiles($_FILES);
        if (!$uploaded_files) {
            return -1;
        }


        
        if (!in_array($widget_type, array('xml', 'php'))) {
            return -1;
        }


        $row = array (
            'widget_name'=>'', 
            'widget_icon' => trim($post_data['widget_icon']),
            'widget_nickname'=>'', 
            'widget_location'=>$_FILES['widget_file']['name'],
            'widget_author'=>$_SESSION['jaram_user_uid'],
            'widget_nickname' => $widget_name,
            'widget_desc' => $post_data['widget_desc'], 
            'reg_date'=>date("c"),
            'modify_date'=>date("c"),
            'widget_status'=>'wait',
            'widget_type'=>$widget_type
        );

        if ($widget_type == 'xml') {
            $xml = simplexml_load_file($file_path);
            $row['widget_name'] = $xml->title;
            $row['widget_status'] = 'allow';
        }
		
		if ($widget_type == 'php') {
			
		}

        $this->dbo->insert('jaram_widget', $row);
        return $this->dbo->lastInsertId();
    }


    static function getWidget($widget_user_id) {
        $dbo = ZendDB::getDBO();
        $widget = $dbo->fetchRow(
                    "SELECT 
                         a.widget_pref, w.widget_nickname, a.widget_user_id
                    FROM
                        jaram_widget_user AS a
                        LEFT JOIN jaram_widget AS w 
                            ON a.widget_id=w.widget_id
                    WHERE
                        a.widget_user_id=:widget_user_id AND a.uid=:uid
                    ", array('widget_user_id'=>$widget_user_id, 'uid'=>$_SESSION['jaram_user_uid']));

        $pref = unserialize($widget['widget_pref']);
        $pref['widget_user_id'] = $widget['widget_user_id'];

        return JaramWidget::getWidgetObj($widget['widget_nickname'], $pref);
    }

    static function getWidgetObj($widget_name, $pref = "") {
        $widget_php_path = WIDGET_PATH.DIRECTORY_SEPARATOR.$widget_name.".php";
        $widget_xml_path = WIDGET_PATH.DIRECTORY_SEPARATOR.$widget_name.".xml";
        
        // PHP일 경우
        if (file_exists($widget_php_path)) {
            require_once $widget_php_path;
            try {
                $obj = new $widget_name($pref);
            } catch (Exception $e) {
                getlogger()->err($e);
            }
            return $obj;
        }


        // XML일 경우
        if (file_exists($widget_xml_path)) {

        }
        return null;
    }



}
?>