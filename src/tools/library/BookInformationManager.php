<?
require_once 'DB.php';
require_once 'BookInformationCollector.php';

class BookInformationManager {
	var $db;


	function connectDB(){
		$dsn = 'mysql://webteam:tpfmwlgh@localhost/neojaram';
		$options = array(
			'debug'       => 2,
			'portability' => DB_PORTABILITY_ALL,
		);

		$this->db =& DB::connect($dsn, $options);
		if (DB::isError($this->db)) {
			 die($this->db->getMessage());
		}
	}

	function addBookInfo($isbn) {
		$collector = new BookInformationCollector($isbn);
		$book = $collector->getBookInfo($isbn);
		return $this->addBookToDB($book);

	}

	function addBookToDB($book){
		$sql = "INSERT INTO jaram_library_books (subject, subject_org, author, translator, publisher, price, price_unit, isbn, publish_date, page, intro, etc1) "
		. " VALUES ("
		. "'" . addslashes($book->getSubject()) . "', "
		. "'" . addslashes($book->getSubjectOriginal()) . "', "
		. "'" . addslashes($book->getAuthor()) . "', "
		. "'" . addslashes($book->getTranslator()) . "', "
		. "'" . addslashes($book->getPublisher()) . "', "
		. "'" . addslashes($book->getPrice()) . "', "
		. "'" . addslashes($book->getPriceUnit()) . "', "
		. "'" . addslashes($book->getISBN()) . "', "
		. "'" . addslashes($book->getPublishDate()) . "', "
		. "'" . addslashes($book->getPage()) . "', "
		. "'" . addslashes($book->getIntro()) . "', "
		. "'" . addslashes($book->getEtc()) . "')";
		$this->connectDB();
		$rs = $this->db->query($sql);
		$this->db->disconnect();
		if(DB::isError($rs)){
			return false;
		} else {
			return true;
		}
	}
}
?>
