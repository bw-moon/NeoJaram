<script language="JavaScript" type="text/JavaScript">
function check_comment()
{
	if(!document.seminar_comment.name.value)
	{
		alert("�̸��� �Է��Ͽ� �ּ���.");
		document.seminar_comment.name.focus();
		return false;
	}
	if(!document.seminar_comment.text.value)
	{
		alert("������ �Է��Ͽ� �ּ���.");
		document.seminar_comment.text.focus();
		return false;
	}
	seminar_comment.submit();
}
</script>