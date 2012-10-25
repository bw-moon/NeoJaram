<?
$widget_factory = true;

// TODO: 이 루틴은 위험할 수 있는 방법이다. 차후 안전을 위한 작업을 취해야 함.
$call_func_name = strtolower(sprintf("%s_%s", $_REQUEST['page'], $_REQUEST['action']));

if (function_exists($call_func_name)) {
	if (call_user_func($call_func_name, $_REQUEST) < 0) {
		echo "작업이 실패했습니다. - ".$call_func_name;
	}
} else {
	// 비정상적인 접근
}



function widget_add_form($data) {
	$smarty = new JaramSmarty();
	$smarty->display('front/widget_form.tpl');
	return 1;
}

function widget_add($data) {
    $widget = new JaramWidget();
    $widget_id =  $widget->addWidget($data);
	p_redirect("./?page=widget&action=list");

	return 1;
}

function widget_list($data) {
	$smarty = new JaramSmarty();
    $widget = new JaramWidget();
	$smarty->assign("widgets", $widget->getWidgets());
	$smarty->display('front/widget_list.tpl');
	return 1;
}

function widget_enable($data) {
    $widget = new JaramWidget();
    $widget->enableWidget($data['id']);
    p_redirect("./?page=widget&action=list");
    return 1;
}

?>