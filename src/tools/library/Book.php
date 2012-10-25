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
		$this->setSubject("Refactoring (�ѱ���)");
		$this->setAuthor("Kent Beck , Martin Fowler , William Opdyke");
		$this->setTranslator("������ , �����");
		$this->setPublisher("��û");
		$this->setPrice("25000");
		$this->setISBN("89-87939-60-x");
		$this->setPublishDate("2002-03-15");
		$this->setIntro("�� å�� ������ �����ϴ� ����Ʈ������ ���ɰ� �������� ���Ἲ�� �����ϱ� ���� ����� �Ұ��ϴ� å����, ������ ���������� ����ȯ�濡 ���缭 ��� �ؾ� ���� ȿ�����̰�, ������ ������ ����Ʈ��� ������ �� �ִ����� ���ؼ� ���������� �����ϰ� �ִ�. ���⼭�� ���� ���� �ڵ�� �� �۾��Ͽ� �ߵ� �ڵ�� ��ȯ�ϴ� ������ ����� �Ұ��ϸ�, �׿� �Բ� \"Refactoring\"�� ���信 ���ؼ� ����� ������ �� �ִ� ��ȸ�� �����Ѵ�. \n�̷��� Refactoring�� ���ؼ� �ǹ��ڵ��� ����Ʈ������ ������ ������ �� ������, �߸��� �ڵ�� ���� �ð��� ����� ������ ���������� �� �� ���� ���̴�. Refactoring�� ���� �� �� å�� ���� ��� ��ο� ����ϰ� �ڼ��� ���θ��� �����ϰ� ������, �װ��� �����ϱ� ���� ���� �Ƴ��� �ʰ� �ִ�. �̷��� ������� �ܰ����� ���ٿ� ���� ���� ���� ���� �� �ֵ��� �Ͽ���. �� å���� �����Ǵ� ��� �������� Java��� ��ü ������� �ۼ� �Ǿ���, ��ü ������ �����ϴ� ��� ������ �� ������ ���� �����ϵ��� �ۼ��Ͽ���.");
		$this->setPage("469");
		$this->setPriceUnit("��");
		$this->setEtc("������");
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