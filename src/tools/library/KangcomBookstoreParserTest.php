<?
require_once 'KangcomBookstoreParser.php';
require_once 'PHPUnit.php';

class KangocmBookstoreParserTest extends PHPUnit_TestCase {
	var $parser;
	function setUp(){
		$this->parser = new KangcomBookstoreParser("http://jaram.org/tools/library/kangcompage_2.html");
		$this->parser->htmlParsing();
	}
	

	function testGetBook(){
		$book = $this->parser->getBook();

		$this->assertEquals("C++ How To Program, 4��", $book->getSubject());
		$this->assertEquals("Harvey M. Deitel, Paul J. Deitel", $book->getAuthor());
		$this->assertEquals("�迵��, ����ö, ������", $book->getTranslator());
		$this->assertEquals("�Ǿ�������̼��ڸ���", $book->getPublisher());
		$this->assertEquals(40000, $book->getPrice());
		$this->assertEquals("8945070745", $book->getISBN());
		$this->assertEquals("2003-09-15", $book->getPublishDate());
		$this->assertEquals(1400, $book->getPage());
		$this->assertEquals("������", $book->getEtc());
		$this->assertEquals("http://kangcom.com/l_pic/200309190004.jpg", $book->getImageUrl());
	}


}


$suite = new PHPUnit_TestSuite(KangocmBookstoreParserTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();

?>