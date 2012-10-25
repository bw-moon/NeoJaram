<?php
require_once('library.inc.php');

class JaramUser extends JaramCommon {
	
    function __construct ($uid) {
        parent::__construct();
		$this->uid = $uid;
		$this->setUser($this->uid);
		$this->imageManager = new JaramImage();
		$this->tagManager = new JaramTag();
    }

	/**
	 * 사용자 정보가 들어있는 배열을 리턴
	 */
	function getUser() {
		return $this->user;
	}

	function setUser($uid) {
		$this->user = $this->dbo->fetchRow("SELECT * FROM jaram_users WHERE uid=:uid", array('uid'=>$uid));
	}

	function setPassword($old_password, $new_password) {
		if ($this->chkPassword($this->user['user_id'], $old_password)) {
			$password = $this->makePassword($this->user['user_id'], $new_password);
			$this->user['user_password'] = $password;
			return true;
		}

		return false;
	}

	function chkPassword($id, $password) {
		$password = $this->makePassword($this->user['user_id'], $password);
		return $this->dbo->fetchOne("SELECT COUNT(*) FROM jaram_users WHERE user_id=:id AND user_password=:password", array('id'=>$id, 'password'=>$password));
	}

	function makePassword($id, $password) {
		return crypt($password, $id, 0, 2);
	}

	function setEmail($email) {
		$this->user['user_email'] = $email;
	}

	function setHomepage($url) {
		$this->user['user_homepage'] = $url;
	}

	function setCellPhone($phone_num) {
		$this->user['user_phone1'] = $phone_num;
	}

	function setJob($job) {
		$data = array('');
		$this->tagManager->saveTag($data);
	}

	function setMessenger($msgr_type, $msgr_id) {
		$this->user['user_msgr_type'] = $msgr_type;
		$this->user['user_msgr_id'] = $msgr_id;
	}

	function setSign($sign) {
		$this->user['user_sign'] = $sign;
	}

	function setBirthday($birth) {
		if (strlen($birth) < 10) {
			if (strlen($birth) == 6) {
				$birth = "19".$birth;
			}
			$birth =  substr($birth, 0, 4)."-".substr($birth, 4, 2)."-".substr($birth, 6, 2);
		}

		$this->user['user_birthday'] = $birth;
	}

	function setUserPicTrue() {
		$this->user['user_having_image1'] = 'true';
	}

	function setUserPicFalse() {
		$this->user['user_having_image1'] = 'false';
	}

	function setSmallPicTrue() {
		$this->user['user_having_image2'] = 'true';
	}

	function setSmallPicFalse() {
		$this->user['user_having_image2'] = 'false';
	}

	function getUserPic() {
		return $this->imageManager->getUserImg($this->uid, 200, 200);
	}

	function getSmallPic() {
		return $this->imageManager->getCommentImg($this->uid);
	}

	function saveUser() {
		return $this->dbo->update('jaram_users', $this->user, "uid='{$this->user['uid']}'");
	}
}



?>