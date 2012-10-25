<?
require_once "library.inc.php";
require_once(JARAM_SMARTY_DIR."Smarty.class.php");

class JaramSmarty extends Smarty
{
	function JaramSmarty() 
	{
		$d = DIRECTORY_SEPARATOR;
		$this->template_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'templates';
		$this->compile_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'templates_c';
		$this->cache_dir = PROGRAM_ROOT.$d.'data'.$d.'smarty'.$d.'cache';
		$this->config_dir = PROGRAM_ROOT.$d.'config';
		$this->caching = TRUE;
		$this->assign('context_root', WEB_ABS_PATH);
		$this->assign('widget_path', WIDGET_PATH);
	}

}
?>