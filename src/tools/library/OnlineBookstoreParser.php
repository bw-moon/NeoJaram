<?
require_once 'HTTP/Client.php';
require_once 'parserdata.dat';
require_once 'KangcomBookstoreParser.php';



class OnlineBookstoreParser {
	var $ypbook_url;
	var $book_url = array();
	var $parser;
	var $bookstore;

	function OnlineBookstoreParser ($isbn) {
		global $bookstore_info;
		//$this->ypbook_url = "http://www.ypbook.com/search.cgi?isbn=".$isbn;
		$this->ypbook_url = "http://jaram.org/tools/library/ypbookpage.html";
		$this->bookstore = $bookstore_info;
	}


	function getWebPageData($url) {
		$client = new HTTP_Client();
		$response = $client->get($url);
		return $client->currentResponse();
	}

	function getBookInfoURL() {
		$webpage = $this->getWebPageData($this->ypbook_url);
		$lines = $this->getStringLine($webpage['body']);
		return $this->parseMetaPage($lines);
	}

	function getStringLine($string) {
		return explode("\n", $string);
	}

	function parseMetaPage($line_array) {
//		$this->book_url = array();
		foreach ($line_array as $value) {
			if (substr($value, 0, 5) == "<p><a") {
				$value = substr($value, strpos($value, "status='")+8);
				$value = substr($value, 0, strpos($value, "'"));
				//$booklink = eregi_replace("([[:alnum:]]+)://([a-z0-9\200-\377\\_\./~@:?=%&\-]+)", "\\1://\\2", $value);
				
				if (strlen($value) > 10) {
//					print "+ ".$value."\n";
					array_push($this->book_url, $value);
				}

			}
		}
//		print_r($this->book_url);
		return $this->book_url;
	}

	function getPromisingLink(){
		$this->getBookInfoURL();
		foreach($this->bookstore as $key => $value){
			foreach ($this->book_url as $link) {
				if (strpos($link, $key)) {
					$this->parser = $value;
					return $link;
				}
			}
		}

		return FALSE;
	}


	function getBookObject() {
		$link = "";
		if ($link = $this->getPromisingLink()) {
			$code = "\$parser_i = new ".$this->parser."(\"\$link\");";
			//print $code;
			eval($code);
			$parser_i->htmlParsing();
			return $parser_i->getBook();
		} else {
			return FALSE;
		}

		
	}

}

?>