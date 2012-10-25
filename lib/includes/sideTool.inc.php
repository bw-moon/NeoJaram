<?php

function make_widget_pref_forms($form_id, $pref, $data) {
	$result = "<form id=\"widget_pref_form_{$form_id}\" name=\"widget_form_{$form_id}\" method=\"get\" action=\"\">\n";
    $result .= "<input type=\"hidden\" name=\"widget_user_id\" value=\"{$form_id}\"/>\n";
	foreach ($pref as $key => $value) {
        $result .= (isset($value['label'])) ? $value['label'] : "";
        $form_value = $data[$key] ? $data[$key] : $value['value'];
		if (!isset($value['type'])) {
			$result .= " <input type=\"text\" name=\"{$key}\" value=\"{$form_value}\" class=\"widget_input\"/><br/>\n";
		} else if ($value['type'] == 'color') {
            $result .= "<input type=\"hidden\" id=\"widget_color_{$form_id}\" name=\"widget_color\" value=\"{$form_value}\"/>\n";
            foreach ($value['item'] as $color) {
                $result .="<a href=\"javascript:change_widget_color('{$form_id}', '{$color}', this)\"><img src=\"".WEB_ABS_PATH."/images/color_{$color}.gif\" width=\"10\" height=\"10\" alt=\"{$color}\"/ class=\"widget_color\"></a> ";
            }
            $result .="<br/>\n";
        }
	}
	$result .= "<input type=\"button\" value=\"저장\" onclick=\"javascript:save_widget_pref('{$form_id}')\"/>";
	$result .= "</form>\n";
	return $result;
}

$dbo = ZendDB::getDBO();

$widget_engine = new JaramWidget();
$widgets = $widget_engine->getWidgetInstance($_SESSION['jaram_user_uid']);
$compiled_widgets = array();
$widget_id_list = array();
try {
	foreach ($widgets as $widget) {
        $pref = unserialize($widget['widget_pref']);
        $widget_obj = JaramWidget::getWidgetObj($widget['widget_nickname'], $pref);
        $widget['widget_obj'] = $widget_obj;
		$widget['widget_pref'] = make_widget_pref_forms($widget['widget_user_id'], $widget_obj->widget_pref, $pref);
		$compiled_widgets[] = $widget;
        $widget_id_list[$widget['widget_user_id']] = array('widget_hide' => $widget['widget_hide'], 'widget_order' => $widget['sort_order'], 'widget_status'=>$widget['widget_status']);
	}
} catch (Exception $e) {
	$logger = getLogger();
	$logger->err($e);
}


$smarty = new JaramSmarty();

if ($widget_factory) {
	$smarty->assign('widget_container_class', 'factory');
} else {
	$smarty->assign('widget_container_class', 'product');
}

require_once 'Zend/Json.php';

$smarty->assign('widgets', $compiled_widgets);
$smarty->assign('widget_id_list', urlencode(Zend_Json::encode($widget_id_list)));
$smarty->display('common/side_bar.tpl');

?>