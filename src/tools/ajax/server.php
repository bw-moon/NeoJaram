<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*
 * Ajax 서버로서의 역할
 * by 18th 문병원 
 */
require_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");

$ajax_logger = getLogger('AjaxLogger');
//$ajax_logger->info("action:".$_REQUEST['action']. ", values : ".implode("|", $_REQUEST));


switch ($_REQUEST['action']) {
	case "insert_widget":
		insert_widget($_REQUEST['widget_id']);
		break;
	case "show_widgets":
		show_widgets();
		break;
    case 'get_widget':
        get_widget($_REQUEST['widget_user_id']);
        break;
	case "delete_widget":
		delete_widget($_REQUEST['widget_user_id']);
		break;
	case "widget_position":
		update_widget("sort_order", $_REQUEST['widget_container']);
		break;
	case "widget_toggle":
		update_widget("toggle", $_REQUEST['widget_user_id']);
		break;
	case "delete_msg":
		delete_msg($_REQUEST['msg_id']);
		break;
    case "delete_comment":
        delete_comment($_REQUEST['comment_id']);
        break;
    case "save_draft_article":
        save_draft_article($_POST);
        break;
    case "delete_draft_article":
        delete_draft_article($_POST);
        break;
    case "save_widget_pref":
        save_widget_pref($_POST);
        break;
    case "get_icon_list":
        get_icon_list($_REQUEST);
        break;
	case "get_group_list":
		get_group_list($_REQUEST);
		break;
    case "process_widget":
        process_widget($_REQUEST);
        break;
	case "get_tag":
		get_tag_list($_REQUEST);
		break;
	case "save_tag":
		save_tag($_REQUEST);
		break;
	case "delete_tag":
		delete_tag($_REQUEST);
		break;
	default:
		$ajax_logger->warning("undefined action recieve. consider web attack!");
        $ajax_logger->info($_REQUEST);
		break;
}

function check_widget($widget_id, $check_type = 'valid') {
	$dbo = ZendDB::getDBO();
	$result = $dbo->fetchRow("SELECT * FROM jaram_widget WHERE widget_id=:widget_id", array('widget_id'=>$widget_id));
	switch($check_type) {
		case "valid":
			return count($result) > 0;
		default:
			return false;
	}
}

function update_widget($type, $data) {
	$GLOBALS['ajax_logger']->debug("update widget start");
	if (!is_login() || !$type || !$data) return false;

	$dbo = ZendDB::getDBO();
	$result = false;
	switch ($type) {
		case "sort_order":
			foreach ($data as $key => $value) {
				$where = $dbo->quoteInto('widget_user_id=?', $value);
				$set = array('sort_order' => $key);
				$result = $dbo->update('jaram_widget_user', $set, $where);
			}
			break;
		case "toggle":
			$dbo->query("UPDATE jaram_widget_user SET widget_hide = NOT(widget_hide) WHERE widget_user_id=:widget_user_id", array('widget_user_id'=>$data));
		default:
			$result = false;
	}
	return $result;
}

function insert_widget($widget_id) {
	if (!is_login() || !check_widget($widget_id) || !$widget_id) return false;
	
	$dbo = ZendDB::getDBO();
	$sort_order = $dbo->fetchOne("SELECT MAX(sort_order) FROM jaram_widget_user WHERE uid=:uid", array('uid'=>$_SESSION['jaram_user_uid']));
	$row = array('uid'=>$_SESSION['jaram_user_uid'], 'widget_id'=>$widget_id, 'sort_order'=>$sort_order+1);
	$GLOBALS['ajax_logger']->debug($row);
	try {
		$result = $dbo->insert('jaram_widget_user', $row);
	} catch (Exception $e) {
		$GLOBALS['ajax_logger']->err($e);
		return false;
	}
	return $result;
}

function delete_widget($widget_user_id) {
	if (!is_login() || !$widget_user_id) return false;
	$GLOBALS['ajax_logger']->debug("delete widget start");
	$dbo = ZendDB::getDBO();
	$where = $dbo->quoteInto('widget_user_id = ?', $widget_user_id);
	$result = $dbo->delete('jaram_widget_user', $where);
	return $result;
}

function show_widgets() {
	$widget_factory = false;
	require_once INCLUDE_PATH.DIRECTORY_SEPARATOR."sideTool.inc.php";
}

function get_widget($widget_user_id) {
    $obj = JaramWidget::getWidget($widget_user_id);
    if ($obj != null) {
        header('Content-type: text/xml');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <result>
        <content><![CDATA[
        ".$obj->getContent()."
        ]]></content>
        <script><![CDATA[
        ".$obj->getTriggerScript()."
        ]]>
        </script>
        </result>";
    }
}

function process_widget($post_data) {
    $obj = JaramWidget::getWidget($post_data['widget_user_id']);
    echo $obj->$post_data['method']($post_data);
}

function save_widget_pref($post_data) {
    $widget = new JaramWidget();
    $widget->updateWidgetPref($post_data);
}

function delete_msg($msg_id) {
	$dbo = ZendDB::getDBO();
	$where = $dbo->quoteInto('msg_id=?', $msg_id);
	$where .= " AND ".$dbo->quoteInto('uid=?', $_SESSION['jaram_user_uid']);
	$row = array('status'=>'delete');
	$dbo->update('jaram_one_line_user', $row, $where);
}

function delete_comment($comment_id) {
    $board = new JaramBoard();
    return $board->deleteComment($comment_id);
}


function save_draft_article($post_data) {
    header('Content-type: text/xml');
    $board = new JaramBoard($post_data['tableID']);
    $id = $board->saveDraftArticle($post_data);
    $msg = date("h시 i분 s초")." 경에 자동으로 저장되었습니다.";
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?><result><id>{$id}</id><msg><![CDATA[{$msg}]]></msg></result>";
}


function delete_draft_article($post_data) {
    header('Content-type: text/xml');
    $board = new JaramBoard($post_data['tableID']);
    $id = $board->cleanDraftArticle($post_data['draft_id']);
    $msg = "임시로 저장되었던 글이 삭제되었습니다";
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?><result><msg>{$msg}</msg></result>";
}

function get_icon_list($post_data) {
    $icons = glob(HOMEPAGE_PATH."/images/icons/{$post_data['widget_icon']}*.gif");
    echo "<ul>\n";
    foreach ($icons as $icon) {
        $filename = basename($icon, ".gif");
        echo "<li><img src=\"".WEB_ABS_PATH."/images/icons/{$filename}.gif\"> {$filename}</li>";
    }
    echo "</ul>\n";
}

function get_group_list($post_data) {
	$dbo = ZendDB::getDBO();
	$list = $dbo->fetchAll("SELECT * FROM jaram_groups WHERE group_name LIKE '%{$post_data['seminar_group_name']}%'");

	echo "<ul>\n";
    foreach ($list as $group) {
		$spotlight = str_replace($post_data['seminar_group_name'], "<strong>{$post_data['seminar_group_name']}</strong>", $group['group_name']);
        echo "<li>{$spotlight}</li>";
    }
    echo "</ul>\n";

}

function get_tag_list($data) {
	$tag_obj = new JaramTag();
	$list = $tag_obj->getAutoCompleteList($data[$data['form_field']], $data['field']);

	echo "<ul>\n";
    foreach ($list as $tag) {
        echo "<li>{$tag['tag_name']}</li>\n";
    }
    echo "</ul>\n";

}

function save_tag($data) {
	$tag_obj = new JaramTag();
	$data['tag_name'] = urldecode($data['tag_name']);
	$tag_use_id = $tag_obj->saveTag($data['tag_name'], $data['tag_type'], $data['tag_use_field']);

	echo "<li id=\"{$data['tag_use_field']}_{$tag_use_id}\">{$data['tag_name']} <a href=\"javascript:delete_tag('{$data['tag_use_field']}','{$tag_use_id}')\"><img src=\"images/icons/cross.gif\" alt=\"delete\"></a></li>";
}

function delete_tag($data) {
	$tag_obj = new JaramTag();
	$tag_obj->deleteTag($data['tag_use_id']);
}

?>