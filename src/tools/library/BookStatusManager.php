<?
require_once 'DB.php';

class BookStatusManager {
	/*
	 * DB���� ���̴� status
	 * R : �ݳ�(Return)
	 * L : ����(Lend)
	 * D : ����(Defer)
	 * V : ����(reserVation)
	 * C : �������(Cancel)
	 */


	var $db;
	var $lend_period;


	function BookStatusManager() {
		$this->lend_period = 3600 * 24 * 14;
		$this->connectDB();
	}

	function connectDB(){
		$dsn = 'mysql://webteam:tpfmwlgh@localhost/neojaram';

		$options = array(
			'debug'       => 2,
			'portability' => DB_PORTABILITY_ALL,
		);

		$this->db =& DB::connect($dsn, $options);
		if (DB::isError($this->db)) {
			 die($this->db->getMessage());
		} else {
			return TRUE;
		}
		return FALSE;
	}

	function lendBook($bid, $uid) {
		if ($this->isLendAble($bid)) {
			$sql = "INSERT INTO jaram_library_book_status (bid, status, uid, date) VALUES ('?','L','?','?');";
			$result =& $this->db->query($sql, array($bid, $uid, time()));
			if (DB::isError($result)) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	//TODO :: �ڽ��� ������ �ϰ�� ������ �ֵ��� �ٲ��ش�
	function isLendAble($bid) {
		$sql = "SELECT status, uid FROM jaram_library_book_status WHERE bid='?' ORDER BY id DESC LIMIT 1;";
		$result =& $this->db->query($sql, $bid);

		if (DB::isError($result)) {
			die($result->getMessage());
		} else {
			$row =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			if ($result->numRows() < 1 || $row['status'] == "R") {
				return TRUE;
			}

		}
		return FALSE;
	}


	function isReturnAble($bid) {
		$sql = "SELECT status FROM jaram_library_book_status WHERE bid='?' AND status<>'C' AND status<>'V' ORDER BY id DESC LIMIT 1;";
		$result =& $this->db->query($sql, $bid);
//		echo $sql;

		if (DB::isError($result)) {
			die($result->getMessage());
			return FALSE;
		} else {
			$row =& $result->fetchRow(DB_FETCHMODE_ASSOC);
//			print_r($row);
			if ($row['status'] == "L" || $row['status'] == "D") {
				return TRUE;
			}
		}
		return FALSE;
	}

	function returnBook($bid, $uid) {
		if ($this->isReturnAble($bid)) {
			$sql = "INSERT INTO jaram_library_book_status (bid, status, uid, date) VALUES ('?','R','?','?');";
			$result =& $this->db->query($sql, array($bid, $uid, time()));

			if (DB::isError($result)) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}

	function isDelayed($bid) {
		$sql = "SELECT date FROM jaram_library_book_status WHERE bid='?' AND (status='L' OR status='D') ORDER BY id DESC LIMIT 1;";
		$result =& $this->db->query($sql, $bid);

		if (DB::isError($result)) {
			return FALSE;
		} else {
			$row =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			if (time() - $row['date'] > $this->lend_period) {
				return TRUE;
			}
		}
	}


	function isDeferAble ($bid, $uid) {
		//������� �ʰ� ���� �����Ҷ�
		//������ ���� �ٸ� å�� ���� ���� �ʾ�����

		if($this->isReservable($bid) == TRUE && $this->isDelayed($bid) == FALSE){
			$sql = "SELECT bid FROM jaram_library_book_status WHERE uid='?' AND (status='L' OR status='D') GROUP BY bid;";
			$result =& $this->db->query($sql, $uid);

			if (DB::isError($result)) {
				return FALSE;
			} else {
				while($row =& $result->fetchRow(DB_FETCHMODE_ASSOC)){
					if($this->isDelayed($row['bid'])){
						return FALSE;
					}
				}
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}

	function deferBook ($bid, $uid) {
		if($this->isDeferAble($bid, $uid)){
			$sql = "INSERT INTO jaram_library_book_status (bid, status, uid, date) VALUES ('?','D','?','?');";
			$result =& $this->db->query($sql, array($bid, $uid, time()));

			if (DB::isError($result)) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	//��å�� �������� ������ �޴´�;;
	function isReservable($bid) {
		$sql = "SELECT status FROM jaram_library_book_status WHERE bid='?' AND status<>'C' ORDER BY id DESC LIMIT 1";
		$result =& $this->db->query($sql, $bid);

		if (DB::isError($result)) {
			die($result->getMessage());
			return FALSE;
		} else {
			$row =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			if ($row['status'] == "L" || $row['status'] == "D" || $row['status'] == "V") {
				return TRUE;
			}
		}

		return FALSE;
	}

	function reserveBook($bid, $uid) {
		if ($this->isReservable($bid)) {
			$sql = "INSERT INTO jaram_library_book_status (bid, status, uid, date) VALUES ('?','V','?','?');";
			$result =& $this->db->query($sql, array($bid, $uid, time()));

			if (DB::isError($result)) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	//������ ���� -��- ���̾���...
	function isReservedBook($bid, $uid){
		$sql = "SELECT status FROM jaram_library_book_status WHERE bid='?' AND uid='" . $uid . "' ORDER BY id DESC LIMIT 1;";
		$result =& $this->db->query($sql, $bid);

		if (DB::isError($result)) {
			die($result->getMessage());
			return FALSE;
		} else {
			$row =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			if ($row['status'] == "V"){
				return TRUE;
			}
		}
		return FALSE;
	}

	function cancelReservedBook($bid, $uid){
		if($this->isReservedBook($bid, $uid)){
			$sql = "INSERT INTO jaram_library_book_status (bid, status, uid, date) VALUES ('?', 'C', '?', '?');";
			$result = $this->db->query($sql, array($bid, $uid, time()));

			if (DB::iSError($result)) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
		
	}

}

?>