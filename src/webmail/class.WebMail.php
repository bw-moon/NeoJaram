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
		global $user_id, $user_password; // ���� ������ ��й�ȣ.. ��, ������������

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

				// �̰� �� ��������.   ���� ������ ���ߵ� ÷�������� �ִ� ���� body�� �������Դϴ�.
				// �� ÷�������� �ΰ��� text ������ ��� body�� ������ ������..
				// �� ������ body�� ���� ǥ���ϴ� ���� �ǰڽ��ϴ�.
				
				// attribute 'parts' includes body divide by boundary
				for($i=0; $i<count($struct->parts); $i++) { 

					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					
					// filename decoding if it is appending file
					$file_name = $this->Decode($param->value); 
					
					$mime = $part->subtype; // return MIME type or mail type
					$encode = $part->encoding; // return encoding method

					// �Ʒ� �κ��� ���� $mime �̶� ������ ALTERNATIVE ��� ���� �ü� �ְ� �Ǿ�
					// �ֽ��ϴ�.. �� OUTLOOK���� HTML �������� ÷�������� ������ �뷫
					// - �޼��� 
					// - ÷������1
					// - ÷������2
					// - ÷������3
					// �̷��� ������ �ٽ� �޼����� 
					// ---PLAN
					// ---HTML
					// �̷��� ������ �˴ϴ�.. �̰�� �޼����� �ش��ϴ� �κ��� ALTERNATIVE�� ����..

					if($mime == "ALTERNATIVE") {
						// �ش� part�� ��ȣ�� body���� �� �κи� ���ɴϴ�. �׸��� �̰��� 
						// ȭ�鿡 �������.. �Ʒ� �Լ���.. �̰��� ���� ���� �ǵ�.. ���߿� ��������.
						$val = imap_fetchbody($this->mstream, $no, (string)($numpart+1));
						$this->PrintOutlook($val);
					} else {
						// ÷�������� ��� printbody�Լ��� ȣ���մϴ�.. �̰� �ٷ� �ؿ� �ִ� �Լ���
						// �� �ű⼭ ��������..
						$this->PrintBody($no, $i, $encode, $mime, $file_name);
					}
				}
				break;

			case "HTML":
			case "ALTERNATIVE": // HTML

				for($i=0; $i<count($struct->parts); $i++) {
					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					$file_name = $this->Decode($param->value); // ÷�������� ��� ���ϸ�
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
					$file_name = $this->Decode($param->value); // ÷�������� ��� ���ϸ�
					$mime = $part->subtype; // MIME Ÿ��
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

		// �׸��� ���ڰ����� �Ѿ�� $encode �� ���� ���� ������ decoding ���ݴϴ�.
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
				// ÷�������� ����̹Ƿ� �ٿ�ε� �� �� �ְ� ��ũ�� �ɾ� �ݴϴ�.
				echo "<br/><br/><hr size=\"1\" color=\"black\"/>\n";
				echo "÷��:<a href=\"gen_mail_down.php?no=".$no."&part_no=".$numpart."\" target=\"_blank\">".$file_name."</a>";
		}
	} // end of function

	// DONE :: base64_decode�Լ��� ���ڵ��ϸ� �� ����. �̻��� -��-
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

		$this->recent = $header->Recent; // ������ ����
		$this->unseen = $header->Unseen; // ������ �о����� ����
		$this->answered = $header->Answered; // �����ߴ��� ����
		$this->deleted = $header->Deleted; // ���� �Ǿ����� ����
		$this->flagged = $header->Flageed; // �÷��� �Ǿ����� ����
		$this->msgno = trim($header->Msgno); // ���Ϲ�ȣ
		$this->date = date("Y/m/d H:i:s", $header->udate); // ������ ��¥
		$this->subject = $mail->Decode($header->Subject); // ����

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
	var $cc; // ����
	var $bcc; // ���� ����

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