<?php

class JaramCommon {
    protected $dbo = null;
    protected $logger = null;
    protected $config = null;
    
    function __construct () {
        $this->dbo = ZendDB::getDBO();
        $this->logger = JaramLogger::getLogger();
        $this->config = JaramConfig::getConfig();
		$this->init();
    }

	function init() {

	}

}
?>