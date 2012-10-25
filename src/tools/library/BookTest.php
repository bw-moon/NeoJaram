<?
require_once 'Book.php';
require_once 'PHPUnit.php';

class BookTest extends PHPUnit_TestCase {
	var $book;

	function BookTest($name){
		$this->PHPUnit_TestCase($name);
	}

	function setUp(){
		$this->book = new Book();
	}

	function testImageUrl(){
		$str = "http://kangcom.com/b_pic/200402180007.jpg";
		$this->book->setImageUrl($str);
		$this->assertEquals($str, $this->book->getImageUrl());
	}

	function testSubject(){
		$str = "º½ÀÌ¿À¸é";
		$this->book->setSubject($str);
		$this->assertEquals($str, $this->book->getSubject());
	}

	function testAuthor(){
		$str = "lee";
		$this->book->setAuthor($str);
		$this->assertEquals($str, $this->book->getAuthor());
	}

	function testTranslator(){
		$str = "outlet";
		$this->book->setTranslator($str);
		$this->assertEquals($str, $this->book->getTranslator());
	}

	function testPublisher(){
		$str = "info culture";
		$this->book->setPublisher($str);
		$this->assertEquals($str, $this->book->getPublisher());
	}

	function testPrice(){
		$str = 100000;
		$this->book->setPrice($str);
		$this->assertEquals($str, $this->book->getPrice());
	}

	function testISBN(){
		$str = "24092049309";
		$this->book->setISBN($str);
		$this->assertEquals($str, $this->book->getISBN());
	}

	function testPublishDate(){
		$str = "2004-4-20";
		$this->book->setPublishDate($str);
		$this->assertEquals($str, $this->book->getPublishDate());
	}

	function testPage(){
		$str = 1000;
		$this->book->setPage($str);
		$this->assertEquals($str, $this->book->getPage());
	}

	function testEtc(){
		$str = "etc";
		$this->book->setEtc($str);
		$this->assertEquals($str, $this->book->getEtc());
	}

	function testSubjectOriginal(){
		$str = "original subject";
		$this->book->setSubjectOriginal($str);
		$this->assertEquals($str, $this->book->getSubjectOriginal());
	}

	function testIntro(){
		$str = "intro ";
		$this->book->setIntro($str);
		$this->assertEquals($str, $this->book->getIntro());
	}
}

$suite = new PHPUnit_TestSuite(BookTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();

?>

