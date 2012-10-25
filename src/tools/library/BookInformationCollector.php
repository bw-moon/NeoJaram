<?
require_once 'OnlineBookstoreParser.php';

class BookInformationCollector {
	var $ISBN;

	function BookInformationCollector($isbn){
		$this->ISBN = $isbn;
	}


	function getBookInfo() {
		// TODO ��Ȳ�� ���� �����ϰ� å�� ������ ��� ����� �����ϵ���
		return $this->getBookInfoOB();
	}

	function getBookInfoOB() {
		$parser = new OnlineBookstoreParser($this->ISBN);
		return $parser->getBookObject();

	}

	


	
}


?>