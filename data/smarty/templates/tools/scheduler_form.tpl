<h3>일정을 입력합니다</h3>
<ul>
<li>D-Day에 적은 날짜 이전부터 홈페이지 첫화면에 카운트를 보여줍니다. D-Day의 최대 기간은 10일입니다.</li>
<li>Mailling 폼에 적은 날짜전에 일정대상을 상대로 일정의 내용을 메일로 보내드립니다. 최대 기간은 20일입니다.</li>
<li>일정이 입력되는 시점에서 일정까지의 남은 기간보다 큰 수를 입력시 기능이 비활성화 됩니다.</li>
</ul>

<form action="./posting.php" method="post" name="post" onsubmit="return checkForm()">
<dl>
<dt>일정 제목</dt>
<dd><input type="text" name="subject" size="45" maxlength="100" value="" class="fullSize"/></dd>
<dt>일정 내용</dt>
<dd>
<textarea name="text" rows="15" cols="35" class="fullSize"></textarea>
</dd>
<dt></dt>
<dd>
<input type="text" name="dday" size="1" maxlength="2"/> 일 전부터 D-day표시 / 
<select name="group">
	{html_options options=$groups selected=$gid}
</select>
그룹에게 <input type="text" name="mailing" size="1"  maxlength="2"/> 일 전에 메일로 공지 / <input type="checkbox" name="seminar" id="seminar" value="1" style="vertical-align:middle"/> <label for="seminar">세미나 입력</label>
</dd>
<dt>
</dt>
<dd>
    <input type="hidden" name="date" value="<?=$_GET[date]?>"/>
    <input type="hidden" name="mode" value="<?=$_GET[mode]?>"/>
    <input type="submit" value="일정 입력"/>&nbsp;&nbsp;<input type="button" value="입력 취소"/>
</dd>
</dl>
</form>