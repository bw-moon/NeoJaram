<?php
require_once('library.inc.php');
require_once('Smarty.class.php');

class JaramSmarty extends Smarty
{
	function JaramSmarty() 
	{
		$d = DIRECTORY_SEPARATOR;
		$this->template_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'templates';
		$this->compile_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'templates_c';
		$this->cache_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'cache';
		$this->config_dir = PROGRAM_ROOT.$d.'config';
		$this->assign('context_root', WEB_ABS_PATH);
		$this->assign('widget_path', WIDGET_PATH);
		$this->caching = FALSE;

        $this->init();

	}

    function init() {
        if (!is_dir($this->template_dir)) {
            mkdir($this->template_dir);
        }

        if (!is_dir($this->compile_dir)) {
            mkdir($this->compile_dir);
        }

        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir);
        }

		if (is_login()) {
			$this->assign('is_login', true);
		}
    }

}

?>