<?php
require_once('library.inc.php');

class JaramConfig {
	public static $config = null;

	/**
	 * 프로그램의 위치가 바뀌는 경우 이곳에 있는 설정파일 절대 경로를 수정해야 함.
	 */
    public static function getConfig($status = 'dev') {
        if (is_null(JaramConfig::$config)) {
            require_once 'Zend/Config/Ini.php';
            JaramConfig::$config = new Zend_Config_Ini('d:/dev/jaram/config/config.ini', $status);
        }

        // 개발버전에서는 개발 설정파일을 사용
        return JaramConfig::$config;
    }

}
?>