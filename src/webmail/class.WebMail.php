<?
/* 
 * gen_mail_class.WebMail.php
 * 
 * web mail (send, receive, list, delete, download appending files)
 * using 143 IMAP port.
 *
 */

class WebMail
{	
	/*
	 * Member Variable
	 */
	var $mstream; // IMAP mailbox stream
	var $mnum; // reference variable of MailNumber
	var $list; // mail list in mail box (only on mail box in POP3)

	var $MailNumber; // mail number

	// constructor
	function WebMail()
	{
		global $user_id, $user_password; // 메일 계정과 비밀번호.. 즉, 리눅계정정보

		$MailNumber = array("total"=>0, "recent"=>0);

		if (!$user_id || !$user_password) {
			die("can't login");
		}

		// open IMAP mailbox stream.
		// if can't connect, die.
		$this->mstream = imap_open("{localhost:143}INBOX", $user_id, $user_password) or die("can't connect: ".imap_last_error());

		// get total and recent mail number in mailbox
		$this->MailNumber["total"] = imap_num_msg($this->mstream);
		$this->MailNumber["recent"] = imap_num_recent($this->mstream);

		// ascending sort the mail by date
		// and fetch the message id to $this->list
		$this->list = imap_sort($this->mstream, SORTDATE, 1);
	}

	// decode 'encode-word'
	// @see RFC 2047
	function Decode($val)
	{	
		if (substr($val, 0, 2) == "=?")
		{	
			$code = strpos($val, "?", 3);
			$code = strpos($val, "?", $code+1);
			$val = substr($val, $code+1, strlen($val) - $code - 3);

			return imap_base64($val);
		}
		else
		{
			return $val;
		}
	}

	// get total and recent mail number in mailbox
	function GetMailNumber()
	{		
		return $this->mnum;
	}

	// analysis mail structure
	function CheckStruct($no) 
	{
		// return structure of mail
		$struct = imap_fetchstructure($this->mstream, $no);
		imap_setflag_full($this->mstream, imap_uid($this->mstream, $no), "\\Seen", ST_UID);

		// type of mail
		// - Plain : plain text
		// - Mixed : with appending files
		// - Alternative : HTML
		// - Related : image inserted HTML
		$type = $struct->subtype; 

		switch($type) 
		{
			case "PLAIN": // plain text

				echo nl2br(imap_fetchbody($this->mstream, $no, "1"));
				break;

			case "MIXED": // with appending files

				// 이게 좀 복잡하죠.   먼저 위에서 말했듯 첨부파일이 있는 것은 body가 여러개입니다.
				// 즉 첨부파일이 두개인 text 메일일 경우 body는 세개로 나뉘죠..
				// 이 세개의 body를 각각 표현하는 것이 되겠습니다.
				
				// attribute 'parts' includes body divide by boundary
				for($i=0; $i<count($struct->parts); $i++) { 

					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					
					// filename decoding if it is appending file
					$file_name = $this->Decode($param->value); 
					
					$mime = $part->subtype; // return MIME type or mail type
					$encode = $part->encoding; // return encoding method

					// 아래 부분을 보면 $mime 이란 변수에 ALTERNATIVE 라는 것이 올수 있게 되어
					// 있습니다.. 즉 OUTLOOK에서 HTML 형식으로 첨부파일을 보내면 대략
					// - 메세지 
					// - 첨부파일1
					// - 첨부파일2
					// - 첨부파일3
					// 이렇게 나뉘고 다시 메세지는 
					// ---PLAN
					// ---HTML
					// 이렇게 나뉘게 됩니다.. 이경우 메세지에 해당하는 부분이 ALTERNATIVE인 거죠..

					if($mime == "ALTERNATIVE") {
						// 해당 part의 번호로 body에서 그 부분만 빼옵니다. 그리곤 이것을 
						// 화면에 출력하죠.. 아래 함수로.. 이것은 제가 만든 건데.. 나중에 설명하죠.
						$val = imap_fetchbody($this->mstream, $no, (string)($numpart+1));
						$this->PrintOutlook($val);
					} else {
						// 첨부파일일 경우 printbody함수를 호출합니다.. 이건 바로 밑에 있는 함수인
						// 데 거기서 설명하죠..
						$this->PrintBody($no, $i, $encode, $mime, $file_name);
					}
				}
				break;

			case "HTML":
			case "ALTERNATIVE": // HTML

				for($i=0; $i<count($struct->parts); $i++) {
					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					$file_name = $this->Decode($param->value); // 첨부파일일 경우 파일명
					$mime = $part->subtype;
					$encode = $part->encoding;

					if($mime == "HTML") {
						$this->PrintBody($no, $i, $encode, $mime, $file_name);
					} 
				}

				break;
			
			case "RELATED": // image inserted HTML

				for($i=0; $i<count($struct->parts); $i++) {
					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					$file_name = $this->Decode($param->value); // 첨부파일일 경우 파일명
					$mime = $part->subtype; // MIME 타입
					$encode = $part->encoding; // encoding
					
					if($mime == "ALTERNATIVE") {
						$val = imap_fetchbody($this->mstream, $MSG_NO, (string)($numpart+1));
						$this->PrintOutlook($val);
					} else {
						$this->PrintBody($no, $i, $encode, $mime, $file_name);
					}
				}
				break;

		} // end of switch
	} // end of function

	// print mail body
	function PrintBody($no, $numpart, $encode, $mime, $file_name) 
	{
		// get body of each part
		$val = imap_fetchbody($this->mstream, $no, (string)($numpart+1));

		// 그리고 인자값으로 넘어온 $encode 에 의해 먼저 본문을 decoding 해줍니다.
		switch($encode) 
		{
			case 0: // 7bit
			case 1: // 8bit
				$val = imap_base64(imap_binary(imap_qprint(imap_8bit($val))));
				break;
			case 2: // binary
				$val = imap_base64(imap_binary($val));
				break;
			case 3: // base64
				$val = imap_base64($val);
				break;
			case 4: // quoted-print
				$val = imap_base64(imap_binary(imap_qprint($val)));
				break;
			case 5: // other
				echo "Unknown encoding type.";
				exit;
		}

		// print value with mime type
		switch($mime) 
		{
			case "PLAIN":
				echo nl2br($val);
				break;
			case "HTML":
				echo $val;
				break;
			default:
				// 첨부파일인 경우이므로 다운로드 할 수 있게 링크를 걸어 줍니다.
				echo "<br/><br/><hr size=\"1\" color=\"black\"/>\n";
				echo "첨부:<a href=\"gen_mail_down.php?no=".$no."&part_no=".$numpart."\" target=\"_blank\">".$file_name."</a>";
		}
	} // end of function

	// DONE :: base64_decode함수로 디코딩하면 잘 보임. 이상해 -ㅁ-
	function PrintOutlook($val) 
	{
		// now working
		$pos = strpos($val, "base64") + strlen("base64");
		$val = substr($val, $pos);
		echo base64_decode($val);
	}

	// return mail stream variable
	function GetMailStream()
	{
		return $this->mstream;
	}

	// download appending file
	function MailDown($no, $numpart, $encode, $mime, $file_name)
	{
		$value = imap_fetchbody($this->mstream, $no, (string)($numpart+1));
		// get body of each part

		switch($encode) 
		{
			case 0: // 7bit
			case 1: // 8bit
				$value = imap_base64(imap_binary(imap_qprint(imap_8bit($value))));
				break;
			case 2: // binary
				$value = imap_base64(imap_binary($value));
				break;
			case 3: // base64
				$value = imap_base64($value);
				break;
			case 4: // quoted-print
				$value = imap_base64(imap_binary(imap_qprint($value)));
				break;
			case 5: // other
				echo "Unknown encoding type.";
				exit;
		}

		switch($mime) {
			case "PLAIN":
				Header ("Content-Type: text/plain");
				echo nl2br($value);
				break;
			case "HTML":
				Header ("Content-Type: text/html");
				echo $value;
				break;
			case "OCTET-STREAM":
			default:
				Header ("Content-Type: octet-stream");
				Header ("Content-Disposition: attachment; filename=".$file_name);
				echo $value;
		}
	}

	// delete message
	function Delete($no)
	{
		// delete flag
		if (imap_delete($this->mstream, $no)) {
			
			// delete!
			imap_expunge($this->mstream);
			return true;

		} else {

			// delete failure
			return false;
		}
	}

	// send a mail
	function Send()
	{
	}

} // end of class


// simple mail header class for read
class MailHeader
{
	// mail stream
	var $mstream;

	// message flags
	var $recent; // 'R' if recent and seen, 'N' if recent and not seen, ' ' if not recent
	var $unseen; // 'U' if not seen and not recent, ' ' if seen or not seen and recent
	var $answered; // 'A' if answered, ' ' if unanswered
	var $deleted; // 'D' if deleted, ' ' if not deleted
	var $draft; // 'X' if draft, ' ' if not draft
	var $flagged; // 'F' if flagged, ' ' if not flagged

	var $msgno; // message number
	var $date; // date of mail
	var $subject; // mail subject

	var $sender; // information of sender
	var $recipient; // information of recipient

	var $files; // information of appending files

	// constructor
	function MailHeader($mail, $no)
	{
		$this->mstream = $mail->GetMailStream();;

		$header = imap_header($this->mstream, $no);

		$this->recent = $header->Recent; // 새메일 여부
		$this->unseen = $header->Unseen; // 메일을 읽었는지 여부
		$this->answered = $header->Answered; // 응답했는지 여부
		$this->deleted = $header->Deleted; // 삭제 되었는지 여부
		$this->flagged = $header->Flageed; // 플래그 되었는지 여부
		$this->msgno = trim($header->Msgno); // 메일번호
		$this->date = date("Y/m/d H:i:s", $header->udate); // 메일의 날짜
		$this->subject = $mail->Decode($header->Subject); // 제목

		$this->sender = array("name" => "", "address" => "");
		$this->recipient = array("name" => "", "address" => "");

		// get the sender information
		$from_object = $header->from[0];
		$this->sender["name"] = $mail->Decode($from_object->personal);
		$this->sender["address"] = substr($from_object->mailbox . "@" . strtolower($from_object->host), 0, 30);
		if($this->sender["name"] == "") $this->sender["name"] = $this->sender["address"];

		// get the recipient information
		$to_object = $header->to[0];
		$this->recipient["name"] = $mail->Decode($to_object->personal);
		$this->recipient["address"] = substr($to_object->mailbox . "@" . strtolower($to_object->host), 0, 30);
		if($this->recipient["name"] == "") $this->recipient["name"] = $this->recipient["address"];
	}
}

// simple mail body class for send
class MailBody
{
	var $cc; // 참조
	var $bcc; // 숨은 참조

	var $sender; // information of sender
	var $recipient; // information of recipient
	
	var $userfile; // appending file

	var $tag; // using tag true/false

	var $MailServer;

	// constructor
	function MailBody()
	{
		$this->MailServer = "localhost";

		$this->sender = array("name" => "", "address" => "");
		$this->recipient = array("name" => "", "address" => "");
	}
}
?>