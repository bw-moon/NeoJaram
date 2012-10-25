<?
class Book {
	var $imageUrl;
	var $subject;
	var $subject_org;
	var $author;
	var $translator;
	var $publisher;
	var $price;
	var $ISBN;
	var $publishDate;
	var $intro;
	var $page;
	var $priceUnit;

	var $etc;

	function testBook() {
		$this->setImageUrl("http://kangcom.com/l_pic/200204020003.gif");
		$this->setSubject("Refactoring (한글판)");
		$this->setAuthor("Kent Beck , Martin Fowler , William Opdyke");
		$this->setTranslator("윤성준 , 조재박");
		$this->setPublisher("대청");
		$this->setPrice("25000");
		$this->setISBN("89-87939-60-x");
		$this->setPublishDate("2002-03-15");
		$this->setIntro("이 책은 기존에 존재하는 소프트웨어의 성능과 구조적인 무결성을 개선하기 위한 방법을 소개하는 책으로, 갈수록 복잡해지는 개발환경에 맞춰서 어떻게 해야 보다 효율적이고, 재사용이 가능한 소프트웨어를 개발할 수 있는지에 대해서 집중적으로 논의하고 있다. 여기서는 좋지 못한 코드로 재 작업하여 잘된 코드로 변환하는 적절한 방법을 소개하며, 그와 함께 \"Refactoring\"의 개념에 대해서 제대로 이해할 수 있는 기회를 제공한다. \n이러한 Refactoring을 통해서 실무자들은 소프트웨어의 결점을 보완할 수 있으며, 잘못된 코드로 인한 시간과 비용의 낭비라는 딜레마에서 헤어날 수 있을 것이다. Refactoring에 관한 한 이 책은 관련 기술 모두와 방대하고도 자세한 세부명세를 제공하고 있으며, 그것을 적용하기 위한 조언도 아끼지 않고 있다. 이러한 조언들은 단계적인 접근에 의해 보다 쉽게 익힐 수 있도록 하였다. 이 책에서 제공되는 모든 예제들은 Java라는 객체 지향언어로 작성 되었고, 객체 지향을 지원하는 어떠한 언어에서도 그 개념이 적용 가능하도록 작성하였다.");
		$this->setPage("469");
		$this->setPriceUnit("원");
		$this->setEtc("번역서");
	}

	//get
	function getPriceUnit(){
		return $this->priceUnit;
	}

	function getImageUrl(){
		return $this->imageUrl;
	}

	function getSubject(){
		return $this->subject;
	}

	function getSubjectOriginal(){
		return $this->subject_org;
	}

	function getIntro(){
		return $this->intro;
	}

	function getAuthor(){
		return $this->author;
	}

	function getTranslator(){
		return $this->translator;
	}

	function getPublisher(){
		return $this->publisher;
	}

	function getPrice(){
		return $this->price;
	}

	function getISBN(){
		return $this->ISBN;
	}

	function getPublishDate(){
		return $this->publishDate;
	}

	function getPage(){
		return $this->page;
	}

	function getEtc(){
		return $this->etc;
	}

	//set
	function setPriceUnit($unit){
		$this->priceUnit = $unit;
	}

	function setImageUrl($url){
		$this->imageUrl = $url;
	}

	function setSubject($subject){
		$this->subject = $subject;
	}

	function setSubjectOriginal($subject_org){
		$this->subject_org = $subject_org;
	}

	function setIntro($intro){
		$this->intro = $intro;
	}
	
	function setAuthor($author){
		$this->author = $author;
	}

	function setTranslator($translator){
		$this->translator = $translator;
	}

	function setPublisher($publisher){
		$this->publisher = $publisher;
	}

	function setPrice($price){
		$this->price =  $price;
	}

	function setISBN($ISBN){
		$this->ISBN = str_replace("-", "", $ISBN);
	}

	function setPublishDate($date){
		$this->publishDate = $date;
	}

	function setPage($page){
		$this->page = $page;
	}

	function setEtc($etc){
		$this->etc = $etc;
	}
}
?>