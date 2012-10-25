<?php
class ZendDB {
	public static $dbo = null;

	public static function getDBO() {	
		if (is_null(ZendDB::$dbo)) {
			require_once "Zend/Db.php";
            $config = JaramConfig::getConfig();

			$params = array ('host'     => $config->database->host,
						 'username' => $config->database->username,
						 'password' => $config->database->password,
						 'dbname'   => $config->database->dbname);
			ZendDB::$dbo = Zend_Db::factory($config->database->type, $params);
        	ZendDB::$dbo->getProfiler()->setEnabled(true);
		}
        return ZendDB::$dbo;
	}
}
?>