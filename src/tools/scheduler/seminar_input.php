<script type="text/javascript" language="JavaScript">
function checkForm() {
	if(!document.post.topic.value) {
		alert("세미나 주제를 입력하여 주십시요");
		document.post.topic.focus();
		return false;
	}
	if(!document.post.desc.value) {
		alert("내용을 입력하여 주십시요");
		document.post.desc.focus();
		return false;
	}  
	return true;
} 
</script>

<form action="posting.php" method="post" name="post" onsubmit="return checkForm()">
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr>
	<td width="75"></td>
	<td>
		<span style="font: 11pt 굴림"><b><? $rv = get_each_date($date);?><?=$rv[year]?></b>년 <b><?=$rv[month]?></b>월 <b><?=$rv[day]?></b>일의 세미나를 입력합니다.</span><br/>
	</td>
</tr>
<tr>
	<td align="right"><b>topic</b></td>
	<td><input type="text" name="topic" size="45" maxlength="100" value="<?=$_POST[subject]?>" class="inputtext" style="width:100%;height:20px"/></td>
</tr>
<tr>
	<td align="right"><b>description</b></td>
	<td><textarea name="desc" rows="20" cols="35" class="inputtext" style="width:100%;"></textarea></td>
</tr>
<tr>
	<td align="right"><b>group</b></td>
	<td><? show_groups("seminar_group");?>&nbsp;&nbsp;세미나 하는 사람</td>
</tr>
<tr>
	<td colspan="2" align="center" height="40">
	<input type="image" value="Post Seminar" src="/images/button/btn_post.gif"/>&nbsp;&nbsp;<a href="/tools/scheduler/"><img src="/images/button/btn_cancel.gif" border="0"/></a>
	</td>
</tr>
</table>
<input type="hidden" name="subject" value="<?=$_POST[subject]?>"/>
<input type="hidden" name="text" value="<?=$_POST[text]?>"/>
<input type="hidden" name="date" value="<?=$_POST[date]?>"/>
<input type="hidden" name="mode" value="<?=$_POST[mode]?>"/>
<input type="hidden" name="dday" value="<?=$_POST[dday]?>"/>
<input type="hidden" name="mailling" value="<?=$_POST[mailling]?>"/>
<input type="hidden" name="group" value="<?=$_POST[group]?>"/>
<input type="hidden" name="submode" value="seminar"/>
</form>
<br/><br/>
<!-- <script>
function group_check(){
    f_check.submit();
}
function group_value(){
    var SelNo = f_check.gorup_ok.selectedIndex;
    var SelVe = f_check.gorup_ok.options[SelNo].value;
}
</script>

<iframe src='#' width=0 height=0 style="width:0px;height:0px" name='i_group'></iframe>
<form name='f_check' action='iframe_group.php' method='get' target='i_group'>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
    <td><input type=text name='group_name' value='' nkeyup='group_check()'/>입력해보세요</td>
</tr>
<tr>
    <td height=150 valign=top><div id='DD'></div>
    </td>
</tr>
</table>
&nbsp;&nbsp;
 -->