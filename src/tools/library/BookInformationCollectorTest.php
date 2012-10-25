<?
require_once 'BookInformationCollector.php';
require_once 'PHPUnit.php';

class BookInformationCollectorTest extends PHPUnit_TestCase {
	var $collector;


	function setUp() {
		$this->collector = new BookInformationCollector("8972806765");
	}

	function testGetBookInfoOB() {
		$book = $this->collector->getBookInfoOB();
		$this->assertEquals("8972806765", $book->getISBN());
	}

}

$suite = new PHPUnit_TestSuite(BookInformationCollectorTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();

?>