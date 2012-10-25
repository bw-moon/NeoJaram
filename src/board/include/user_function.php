<?php
// 등록하기 위해 수정한 함수
function ini_get_custom($param) {
	return ini_get($param['var']);
}

// 사용자 함수 등록
$smarty->register_function("fancy_size", "getFancyFileSize");
$smarty->register_function("login_check", "is_login");
$smarty->register_function("get_ini_info", "ini_get_custom");
