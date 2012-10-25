<?
require_once 'PHPUnit.php';

echo "<h2>Book Management System TestCases</h2>";

if ($handle = opendir('/home/jaram/webteam/public_html/jaram/tools/library')) {
	$testfile = array();	
	/* 디렉토리 안을 루프하는 올바른 방법입니다. */
	while (false !== ($file = readdir($handle))) { 
		if (preg_match('/Test.php/i', $file)) {
			array_push($testfile, $file);
		}
	}
	closedir($handle);
}


//print_r($testfile);

foreach ($testfile as $file) {
	echo (shell_exec("php -f ".$file));
}
/*
foreach ($testfile as $file) {
	$suite = new PHPUnit_TestSuite(str_replace(".php", "", $file));
	$result = PHPUnit::run($suite);
	echo $result -> toHtml();
}
*/



echo "<br><b>Test is Complete...</b>";
?>