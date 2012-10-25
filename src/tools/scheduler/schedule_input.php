<script type="text/javascript" language="JavaScript">
function checkForm() {
    if(!document.post.subject.value) {
        alert("세미나 주제를 입력하여 주십시요");
        document.post.subject.focus();
        return false;
    }
    if(!document.post.text.value) {
        alert("내용을 입력하여 주십시요");
        document.post.text.focus();
        return false;
    }
    if(document.post.dday.value > 10) {
        alert("D-day 최대 날짜는 10일입니다.");
        document.post.dday.focus();
        return false;
    }
    if(document.post.mailing.value > 20) {
        alert("Mailing 최대 날짜는 20일입니다.");
        document.post.mailing.focus();
        return false;
    }
    return true;
} 
</script>

<form action="posting.php" method="post" name="post" onsubmit="return checkForm()">
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr>
    <td></td>
    <td>
        <span style="font: 11pt 굴림"><b><? $rv = get_each_date($date);?><?=$rv[year]?></b>년 <b><?=$rv[month]?></b>월 <b><?=$rv[day]?></b>일의 일정을 입력합니다.</span><br/>
        <img src="/images/t.gif" height="10" width="1" alt=""/><br/>
        <ul style="margin:0px;padding-left:15px">
        <li>D-Day에 적은 날짜 이전부터 홈페이지 첫화면에 카운트를 보여줍니다. D-Day의 최대 기간은 10일입니다.</li>
        <li>Mailling 폼에 적은 날짜전에 일정대상을 상대로 일정의 내용을 메일로 보내드립니다. 최대 기간은 20일입니다.</li>
        <li>일정이 입력되는 시점에서 일정까지의 남은 기간보다 큰 수를 입력시 기능이 비활성화 됩니다.</li>
        </ul>
    </td>
</tr>
<tr>
    <td width="40" align="right"><b>subject</b></td>
    <td><input type="text" name="subject" size="45" maxlength="100" value="" class="inputtext" style="width:100%;height:20px" /></td>
</tr>
<tr>
    <td align="right"><b>text</b></td>
    <td>
    <?//include(dirname(__FILE__)."/miniwini.visualEditor.php");?>

   
    <textarea name="text" rows="15" cols="35" class="inputtext" style="width:100%;"></textarea>
    </td>
</tr>
<tr>
    <td align="right"><b>group</b></td>
    <td>
<? 
$whole_group = array_merge(get_group_array(1000, 1300), get_group_array(3000,5000));
$group_select = get_select($whole_group, "group", "gid", "group_name", "1002");
echo $group_select;
?>
    &nbsp;&nbsp;일정의 대상
    </td>
</tr>
<tr>
    <td></td>
    <td>D-Day&nbsp;<input type="text" name="dday" size="2" class="inputtext"/>&nbsp;일&nbsp;/&nbsp;Mailling&nbsp;<input type="text" name="mailing" size="2" class="inputtext"/>&nbsp;일&nbsp;/&nbsp;<input type="checkbox" name="seminar" value="1"/>Seminar</td>
</tr>
<tr>
    <td colspan="2" align="center" height="40" valign="bottom">
    <input type="hidden" name="date" value="<?=$_GET[date]?>"/>
    <input type="hidden" name="mode" value="<?=$_GET[mode]?>"/>
    <input type="image" value="Post Plan" src="/images/button/btn_post.gif"/>&nbsp;&nbsp;<a href="/tools/scheduler/"><img src="/images/button/btn_cancel.gif" border="0"/></a>
    </td>
</tr>
</table>
</form>