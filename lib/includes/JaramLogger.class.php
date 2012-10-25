<?php

class JaramLogger {
    public static $logger = null;

        // 로거를 리턴
    public static function getLogger($ident = "GlobalLogger", $logLevel = LOG_LEVEL) {
        require_once "Log.php";
        require_once "Log/file.php";

        $today_date = date('Ymd', time()); // 오늘 날짜 yyyymmdd형태

        $conf = array( 'buffering' => true, 
                            'lineFormat' => '[%3$s] %2$s %1$s (%5$s %6$s %7$s) %4$s',
                            'timeFormat' => '%Y%m%d %H:%M:%S');
        
        if ($ident == "systemLogger") {
            $conf['lineFormat'] = '[%3$s] %2$s %1$s %4$s';
        }

        $config = JaramConfig::getConfig();
        $log_file_path = PROGRAM_ROOT.$config->log_path.DIRECTORY_SEPARATOR;
   
        if (isDev()) {
            $log_file_name = $log_file_path.STATUS."_jaram.log";
        } else {
            $log_file_name = $log_file_path."jaram_".$today_date.".log";
        }

        $logger = Log::singleton("file",$log_file_name , $ident, $conf);
		//$logger = Log::singleton("file",$log_file_name);
//        $mask = Log::UPTO($logLevel);
//       $logger->setMask($mask);
        return $logger;
     }
 }
 ?>