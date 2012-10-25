<?
require_once 'PHPUnit.php';
require_once 'BookInformationManager.php';
require_once 'Book.php';

class BookInformationManagerTest extends PHPUnit_TestCase{
	var $manager;

	function setUp(){
		$this->manager = new BookInformationManager();
	}

	function testAddBookToDB(){
		$book = new Book();
		$book->testBook();
		$this->assertTrue($this->manager->addBookToDB($book));
	}

	function testAddBookInfo() {
		$this->assertTrue($this->manager->addBookInfo("89-450-7074-5"));
	}

}


$suite = new PHPUnit_TestSuite(BookInformationManagerTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();
?>
