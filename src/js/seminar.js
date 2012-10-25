<script language="JavaScript" type="text/JavaScript">
function check_comment()
{
	if(!document.seminar_comment.name.value)
	{
		alert("이름을 입력하여 주세요.");
		document.seminar_comment.name.focus();
		return false;
	}
	if(!document.seminar_comment.text.value)
	{
		alert("내용을 입력하여 주세요.");
		document.seminar_comment.text.focus();
		return false;
	}
	seminar_comment.submit();
}
</script>