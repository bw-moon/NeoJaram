<?
require_once 'Book.php';
require_once 'HTTP/Client.php';

class KangcomBookstoreParser{
	var $url;
	var $book;
	var $original_body;

	function KangcomBookstoreParser($url){
		$this->url = $url;
		$this->book = new book();

		$client = new HTTP_Client();
		$client->get($this->url);
		$response = $client->currentResponse();
		$this->original_body = $response['body'];
	}

	function getBody(){
		return $this->original_body;
	}

	function htmlParsing(){
		//구분자
		$START_DELIM_BOOKINFO = "<!----- 저자 / 역자 / 출판사 / 판매가 / 마일리지 / 기타 ----->";
		$END_DELIM_BOOKINFO = "<!--3칸-->";
		$COMMENT_PATTERN = "^<!--";

		//필드 이름을 정의한다.
		$NAME_AUTHOR = "저자";
		$NAME_TRANSLATOR = "역자";
		$NAME_PUBLISHER = "출판사";
		$NAME_PRICE = "판매가";
		$NAME_ISBN = "isbn";
		$NAME_ETC = "기타";

		$MONEY_UNIT = "원";
		$MONEY_DELIM = ",";
		$ETC_DELIM = "/";
		$PAGE_UNIT = "쪽";

		$start_pos = strpos($this->original_body, $START_DELIM_BOOKINFO);
		$end_pos = strpos($this->original_body, $END_DELIM_BOOKINFO);
		$body = substr($this->original_body, ($start_pos + strlen($START_DELIM_BOOKINFO)), ($end_pos - $start_pos));
		$arr = split("<!--", $body);

		foreach($arr as $val){
			$val = strip_tags($val);
			$val = str_replace("\r\n", "", $val);
			$val = str_replace("\t", "", $val);

			if($val !== ""){
				$elem = split("-->", $val);
				$key = $elem[0];
				$value = trim($elem[1]);

				switch ($key){
				case $NAME_AUTHOR:
					$this->book->setAuthor($value);
					break;
				case $NAME_TRANSLATOR:
					$this->book->setTranslator($value);
					break;
				case $NAME_PUBLISHER:
					$this->book->setPublisher($value);
					break;
				case $NAME_PRICE:
					$value = trim(str_replace($MONEY_DELIM, "", substr($value, 0, strpos($value, $MONEY_UNIT))));
					$this->book->setPrice($value);
					break;
				case $NAME_ISBN:
					$this->book->setISBN($value);
					break;
				case $NAME_ETC:
					$etcs = split($ETC_DELIM, $value);
					$page =substr($etcs[2], 0, strpos($etcs[2], $PAGE_UNIT));

					$this->book->setPage(trim(str_replace($MONEY_DELIM, "", $page)));
					$this->book->setPublishDate(trim($etcs[1]));
					$this->book->setEtc(trim($etcs[0]));
					break;
				}
			}
		}
		
		$this->parsingSubject();
		$this->parsingImageUrl();
	}

//	function parsing(){
//		//구분자를 정의한다.
//		$DELIMITER_OF_BOOKINFO = "<!----- 저자 / 역자 / 출판사 / 판매가 / 마일리지 / 기타 ----->";
//		$DELIM_FRONT = "###";
//		$DELIM_REAR = "%%%";
//		$TABLE_START = "<table";
//		$TABLE_END = "</table>";
//		$MONEY_UNIT = "원";
//		$MONEY_DELIM = ",";
//		$ETC_DELIM = "/";
//		$PAGE_UNIT = "쪽";
//
//		//필드 이름을 정의한다.
//		$NAME_AUTHOR = "저자";
//		$NAME_TRANSLATOR = "역자";
//		$NAME_PUBLISHER = "출판사";
//		$NAME_PRICE = "판매가";
//		$NAME_ISBN = "isbn";
//		$NAME_ETC = "기타";
//
//		//기본적으로 뜯어논다.
//		$body = $this->getBody();
//		$tablebox = strchr(strchr($body, $DELIMITER_OF_BOOKINFO), $TABLE_START);
//		$length = strpos($tablebox, $TABLE_END);
//		$infoTable =  substr($tablebox, 0, $length + strlen($TABLE_END));
//
//		//주석처리부분을 꽁수로 delimiter를 이용해서 바꾼다. 바꾼이유는 주석을 살리기 위해;;
//		$infoTable = str_replace("<!--", "<i>" . $DELIM_FRONT, $infoTable);
//		$infoTable = str_replace("-->", $DELIM_REAR . "</i>", $infoTable);
//		$contents = trim(strip_tags($infoTable));
//
//		//남아있는것들 정리한다.
//		$contents = str_replace("\r\n", "", $contents);
//		$contents = str_replace("\t", "", $contents);
//		$arr = split($DELIM_FRONT, $contents);
//		
//		foreach($arr as $line){
//			$elem = split($DELIM_REAR, $line);
//			$key = $elem[0];
//			$value = $elem[1];
//
//			switch ($key){
//				case $NAME_AUTHOR:
//					$this->book->setAuthor($value);
//					break;
//				case $NAME_TRANSLATOR:
//					$this->book->setTranslator($value);
//					break;
//				case $NAME_PUBLISHER:
//					$this->book->setPublisher($value);
//					break;
//				case $NAME_PRICE:
//					$value = trim(str_replace($MONEY_DELIM, "", substr($value, 0, strpos($value, $MONEY_UNIT))));
//					$this->book->setPrice($value);
//					break;
//				case $NAME_ISBN:
//					$this->book->setISBN($value);
//					break;
//				case $NAME_ETC:
//					$etcs = split($ETC_DELIM, $value);
//					$page =substr($etcs[2], 0, strpos($etcs[2], $PAGE_UNIT));
//
//					$this->book->setPage(trim(str_replace($MONEY_DELIM, "", $page)));
//					$this->book->setPublishDate(trim($etcs[1]));
//					$this->book->setEtc(trim($etcs[0]));
//					break;
//			}
//		}
//
//		$this->parsingSubject();
//		$this->parsingImageUrl();
//		return true;
//	}

	function parsingSubject(){
		$START_SUBJECT_TAG = "<input type=\"hidden\" id=\"copytitle\" value";
		$END_SUBJECT_TAG = "</a>";

		$body = strchr($this->getBody(), $START_SUBJECT_TAG);
		$body = strchr(substr($body, strlen($START_SUBJECT_TAG)), $START_SUBJECT_TAG);
		$length = strpos($body, $END_SUBJECT_TAG);
		$body = strrchr(substr($body, 0, $length - strlen("\">")), "\"");
		$body = substr($body, 1);

		$this->book->setSubject($body);
	}

	function parsingImageUrl(){
		$START_IMAGE_BLOCK_TAG = "<!----- 책이미지 ----->";
		$START_IMAGE_TAG = "<img src=\"";
		$IMAGE_DIR = "/l_pic";
		$END_IMAGE_TAG = "\"";
		$DOMAIN = "http://kangcom.com";

		$body = strchr($this->getBody(), $START_IMAGE_BLOCK_TAG);
		$body = substr(strchr($body, $START_IMAGE_TAG . $IMAGE_DIR), strlen($START_IMAGE_TAG));
		$length = strpos($body, $END_IMAGE_TAG);
		$body = substr($body, 0, $length);

		$this->book->setImageUrl($DOMAIN . $body);
	}

	function getBook(){
		return $this->book;
	}
}

//Web page Tester;;
/*
$start_time = time();
$parser = new KangcomBookstoreParser("http://kangcom.com/common/bookinfo/bookinfo.asp?sku=200309190004");
echo time() - $start_time;

$start_time = time();
$parser->htmlParsing();
//$parser->parsing();
echo time() - $start_time;


$book = $parser->getBook();
echo $book->getSubject() . "<br>";
echo $book->getAuthor() . "<br>";
echo $book->getTranslator() . "<br>";
echo $book->getPublisher() . "<br>";
echo $book->getPrice() . "<br>";
echo $book->getISBN() . "<br>";
echo $book->getPublishDate() . "<br>";
echo $book->getPage() . "<br>";
echo $book->getEtc() . "<br>";
echo "<img src=\"" . $book->getImageUrl() . "\" >" . "<br>";
*/

?>

