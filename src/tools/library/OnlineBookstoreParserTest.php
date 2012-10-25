<?

require_once 'OnlineBookstoreParser.php';
require_once 'PHPUnit.php';

class OnlineBookstoreParserTest extends PHPUnit_TestCase {
	var $parser;

	function setUp() {
		$this->parser = new OnlineBookstoreParser("8972806765");
	}

	function testGetWebPageData() {
		$this->assertEquals(3, count($this->parser->getWebPageData("http://jaram.org/tools/library/ypbookpage.html")));
	}


	function testGetBookInfoURL() {
				$result =  Array
(
    "http://www.libro.co.kr/books/book_detail.asp?goods_id=0100004621628",
    "http://www.aladdin.co.kr/catalog/book.asp?ISBN=8972806765",
    "http://www.kyobobook.co.kr/category/bookdetail/BookDetailView.jsp?BKIND=KOR&CATE=&BARCODE=9788972806769&CLICK=",
    "http://kangcom.com/common/bookinfo/bookinfo.asp?sku=200305060001",
    "http://www.bandibook.com/search/subject_view.php?code=2306322&reurl=%252Fsearch%252Fisbn_search_ok.php%253Fisbn%253D8972806765",
    "http://www.yes24.com/Goods/FTGoodsView.aspx?goodsNo=362774",
    "http://www.morning365.com/book/book_detail.asp?id_m=book&class_number=1&object_number=2010000099201",
    "http://www.ypbooks.co.kr/ypbooks/WebHome/specdm/specdm.jsp?p_isbn=1117700807"
);
		$this->assertEquals($result, $this->parser->getBookInfoURL());

	}

	function testParseMetaPage() {
		$webpage = $this->parser->getWebPageData("http://jaram.org/tools/library/ypbookpage.html");
		$lines = $this->parser->getStringLine($webpage['body']);
		$result =  Array
(
    "http://www.libro.co.kr/books/book_detail.asp?goods_id=0100004621628",
    "http://www.aladdin.co.kr/catalog/book.asp?ISBN=8972806765",
    "http://www.kyobobook.co.kr/category/bookdetail/BookDetailView.jsp?BKIND=KOR&CATE=&BARCODE=9788972806769&CLICK=",
    "http://kangcom.com/common/bookinfo/bookinfo.asp?sku=200305060001",
    "http://www.bandibook.com/search/subject_view.php?code=2306322&reurl=%252Fsearch%252Fisbn_search_ok.php%253Fisbn%253D8972806765",
    "http://www.yes24.com/Goods/FTGoodsView.aspx?goodsNo=362774",
    "http://www.morning365.com/book/book_detail.asp?id_m=book&class_number=1&object_number=2010000099201",
    "http://www.ypbooks.co.kr/ypbooks/WebHome/specdm/specdm.jsp?p_isbn=1117700807"
);
		$this->assertEquals($result, $this->parser->parseMetaPage($lines));
	}

	function testGetStringLine() {
		$string = "sfsdfdsf\n\nsdfsdf\nsdf\n";
		$this->assertEquals(5, count($this->parser->getStringLine($string)));
	}


	function testGetBookObject() {
		$book = $this->parser->getBookObject();
		$this->assertEquals("8972806765", $book->getISBN());
	}

	function testGetPromisingLink() {
		$rv = "http://kangcom.com/common/bookinfo/bookinfo.asp?sku=200305060001";
		$this->assertEquals($rv, $this->parser->getPromisingLink());
	}

}


$suite = new PHPUnit_TestSuite(OnlineBookstoreParserTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();
?>