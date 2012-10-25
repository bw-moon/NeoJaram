<?
require_once 'BookStatusManager.php';
require_once 'PHPUnit.php';

class BookStatusManagerTest extends PHPUnit_TestCase {
	var $manager;

	function setUp() {
		$this->manager = new BookStatusManager();
	}

	function testLendBook() {
		$this->assertTrue($this->manager->lendBook(1, 12));
		$this->manager->returnBook(1,12);
	}

	function testIsLendAble() {
		$this->assertTrue($this->manager->isLendAble(100));
	}

	function testIsReturnAble() {
		$this->manager->lendBook(4, 12);
		$this->assertTrue($this->manager->isReturnAble(4));
		$this->manager->returnBook(4, 12);
	}

	function testReturnBook() {
		$this->manager->lendBook(4, 12);
		$this->assertTrue($this->manager->returnBook(4, 12));
	}

	function testIsDelayed() {
		$this->manager->lendBook(4, 12);
		$this->assertFalse($this->manager->isDelayed(4));
		$this->manager->returnBook(4, 12);
	}

	function testIsDeferAble() {
		$this->manager->lendBook(4, 12);
		$this->assertTrue($this->manager->isDeferAble(4, 12));
		$this->manager->returnBook(4, 12);
	}

	function testDeferBook() {
		$this->manager->lendBook(4, 12);
		$this->assertTrue($this->manager->deferBook(4, 12));
		$this->manager->returnBook(4, 12);
	}

	function testIsReservable() {
		$this->manager->lendBook(4, 12);
		$this->assertTrue($this->manager->isReservable(4));
		$this->manager->returnBook(4, 12);
	}

	function testReserveBook() {
		$this->manager->lendBook(4,12);
		$this->assertTrue($this->manager->reserveBook(4, 12));
		$this->assertTrue($this->manager->isReservedBook(4, 12));
		$this->assertTrue($this->manager->cancelReservedBook(4, 12));
		$this->manager->returnBook(4, 12);
	}

	function testIsReservedBook(){
		$this->manager->lendBook(4,12);
		$this->manager->reserveBook(4,12);
		$this->assertTrue($this->manager->isReservedBook(4, 12));
		$this->manager->cancelReservedBook(4, 12);
		$this->manager->returnBook(4, 12);
	}

	function testCancelReservedBook(){
		$this->manager->lendBook(4,12);
		$this->manager->reserveBook(4, 12);
		$this->assertTrue($this->manager->cancelReservedBook(4, 12));
		$this->manager->returnBook(4, 12);
	}

}
$suite = new PHPUnit_TestSuite(BookStatusManagerTest);
$result = PHPUnit::run($suite);
echo $result -> toHtml();
?>