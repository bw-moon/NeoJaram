<? // mail_list.php
session_start();

$user_id = $_SESSION["jaram_mail_id"];
$user_password = $_SESSION["jaram_mail_password"];

include "class.WebMail.php" ;

// ������ ����¡ ���� ���
$per_page = 10;
if (!$page) $page = 1;

// WebMail instance
$mail = new WebMail();
?>
<script language="JavaScript">
function mail_delete() {
	var count = 0;

	for (var i=0; i<document.web_mail.length; i++) {
		if (document.web_mail[i].name == 'msg[]' && document.web_mail[i].checked == true) {
			count++;
		}
	}

	if (count != 0) {
		document.web_mail.action = "./gen_mail_delete.php" + document.web_mail.action;
		document.web_mail.submit();
		return;
	} else {
		alert('������ �޽����� �������ּ���!');
		return;
	}
}
</script>

<center>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<th>WebMail</th>
</tr>
</table>

<table width="100%" border="0" cellspacing="5" cellpadding="5">

<tr>
<td colspan="5">
�� <?=$mail->MailNumber["total"]?> ���� ���� �� ���� ���� <?=$mail->MailNumber["recent"]?>���� ������ �ֽ��ϴ�.
</td>
</tr>

<?
if (count($mail->list) == 0) {
	echo "<tr><td colspan=\"5\">�������� ����ֽ��ϴ�.</td></tr>\n";
} else {
	$first = ($page-1) * $per_page;
	$last = $first + ($per_page);
	if ($last > count($mail->list)) $last = count($mail->list);

	$total_page = ceil(count($mail->list) / $per_page);

?>
<tr>
<th><?=$first?>/<?=$last?></th>
<th><?=$page?>/<?=$total_page?></th>
<th>���� ���</th>
<th>��¥</th>
<th>����</th>
</tr>

<form method="post" name="web_mail" action="?page=<?=$page?>">

<?	
	for ($i=$first; $i<$last; $i++) {

		$header = new MailHeader($mail, $mail->list[$i]);

		// recent, unseen, answered, flagged �� ���� �ϴ� ���� flag�� �ִ´�.
		$flag = (!$header->recent)? (!$header->unseen)? (!$header->answered)? $header->flagged : $header->answered : $header->unseen :  $header->recent;

		// if (!$flag) seen...
		$flag = trim($flag);
?>
<tr>
<td align="center"><input type="checkbox" name="msg[]" value="<?=$mail->list[$i]?>"></td>
<td align="center"><?=$flag?></td>
<td><?=$header->sender["name"]?></td>
<td><?=$header->date?></td>
<td><?=($flag!="N")? "": "<b>"?><a href="gen_mail_detail.php?no=<?=$mail->list[$i]?>"><?=($header->subject)? $header->subject: "&nbsp;"?></a><?=($flag!="N")? "": "</b>"?></td>
</tr>

<?
	} // end of for
} // end of if

?>

<tr>
<td colspan="4">
<?=(count($mail->list)!=0)? "<input type=\"button\" onclick=\"mail_delete();\" value=\"������ ���� ����\">" : ""?>
 <input type="button" onclick="window.location='./gen_mail_send.php';" value="���� �ۼ�">
</td>
<td align="right">
<?
if (count($mail->list)!=0) {
	for ($i=1; $i<$total_page+1; $i++) {
		echo "<a href=\"".$PHP_SELF."?page=".$i."\">".$i."</a> \n";
	}
}
?>
</td>
</tr>

</form>
</table>