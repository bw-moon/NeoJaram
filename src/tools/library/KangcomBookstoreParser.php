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
		//������
		$START_DELIM_BOOKINFO = "<!----- ���� / ���� / ���ǻ� / �ǸŰ� / ���ϸ��� / ��Ÿ ----->";
		$END_DELIM_BOOKINFO = "<!--3ĭ-->";
		$COMMENT_PATTERN = "^<!--";

		//�ʵ� �̸��� �����Ѵ�.
		$NAME_AUTHOR = "����";
		$NAME_TRANSLATOR = "����";
		$NAME_PUBLISHER = "���ǻ�";
		$NAME_PRICE = "�ǸŰ�";
		$NAME_ISBN = "isbn";
		$NAME_ETC = "��Ÿ";

		$MONEY_UNIT = "��";
		$MONEY_DELIM = ",";
		$ETC_DELIM = "/";
		$PAGE_UNIT = "��";

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
//		//�����ڸ� �����Ѵ�.
//		$DELIMITER_OF_BOOKINFO = "<!----- ���� / ���� / ���ǻ� / �ǸŰ� / ���ϸ��� / ��Ÿ ----->";
//		$DELIM_FRONT = "###";
//		$DELIM_REAR = "%%%";
//		$TABLE_START = "<table";
//		$TABLE_END = "</table>";
//		$MONEY_UNIT = "��";
//		$MONEY_DELIM = ",";
//		$ETC_DELIM = "/";
//		$PAGE_UNIT = "��";
//
//		//�ʵ� �̸��� �����Ѵ�.
//		$NAME_AUTHOR = "����";
//		$NAME_TRANSLATOR = "����";
//		$NAME_PUBLISHER = "���ǻ�";
//		$NAME_PRICE = "�ǸŰ�";
//		$NAME_ISBN = "isbn";
//		$NAME_ETC = "��Ÿ";
//
//		//�⺻������ �����.
//		$body = $this->getBody();
//		$tablebox = strchr(strchr($body, $DELIMITER_OF_BOOKINFO), $TABLE_START);
//		$length = strpos($tablebox, $TABLE_END);
//		$infoTable =  substr($tablebox, 0, $length + strlen($TABLE_END));
//
//		//�ּ�ó���κ��� �Ǽ��� delimiter�� �̿��ؼ� �ٲ۴�. �ٲ������� �ּ��� �츮�� ����;;
//		$infoTable = str_replace("<!--", "<i>" . $DELIM_FRONT, $infoTable);
//		$infoTable = str_replace("-->", $DELIM_REAR . "</i>", $infoTable);
//		$contents = trim(strip_tags($infoTable));
//
//		//�����ִ°͵� �����Ѵ�.
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
		$START_IMAGE_BLOCK_TAG = "<!----- å�̹��� ----->";
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

