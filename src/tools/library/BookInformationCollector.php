<?
require_once 'OnlineBookstoreParser.php';

class BookInformationCollector {
	var $ISBN;

	function BookInformationCollector($isbn){
		$this->ISBN = $isbn;
	}


	function getBookInfo() {
		// TODO 상황에 따라서 적당하게 책의 정보를 얻는 방법을 선택하도록
		return $this->getBookInfoOB();
	}

	function getBookInfoOB() {
		$parser = new OnlineBookstoreParser($this->ISBN);
		return $parser->getBookObject();

	}

	


	
}


?>