<script language='javascript'>
<!--
	function insert_test()
	{
		var year=document.insert.year.value;
		var month=document.insert.month.value;
		var day=document.insert.day.value;
		var amount=document.insert.amount.value;
		var in_out=document.insert.in_out.value;
		var bank_cash=document.insert.bank_cash.value;
		var uses=document.insert.uses.value;
		var simple=document.insert.simple.value;
		var detail=document.insert.detail.value;
		
	
		if(year=='')
			alert("몇 년인지 안쓰셨잖아요~! ^^");
		else if(month=='')
			alert("몇 월인지 모르겠네요~ ^^");
		else if(month<1 || month>12)
			alert(month + " " + "월 이란 달이 존재하나요? ^^");
		else if(day=='')
			alert("몇 일인지 안쓰셨어요! ^^");
		else if(day<1 || day>31)
			alert(day + " " + "일 이란 날짜가 존재하나요? ^^");
		else if(amount=='')
			alert("금액 칸을 체우시고 다시 눌러주세요. ^^");
		else if(in_out=='')
			alert("수입 인가요? 지출 인가요?");
		else if(bank_cash=='')
			alert("무슨 수단인지 등록해주세요~ ^^");
		else if(uses=='')
			alert("무슨 용도인지 알려주세요~ ^^");
		else if(simple=='')
			alert("잠깐! 내역을 적어주셔야죠!");
		else if(detail=='')
		{
			flag=confirm("세부사항은 기록 안하시겠어요? ^^");
			if(flag==true)
				document.insert.submit();
		}
		else
			document.insert.submit();
	}
-->
</script>

<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/insert.jpg'>
<form action='./insert.php' method='post' name='insert'>
<tr>
<td valign='top'>
	<table width='340' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='middle'>
		<div align='left'><font size='2'>&nbsp;&nbsp;&nbsp;<b>|</b>&nbsp;INSERT FORM</font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td valign='top'>
	<!--년 월 일-->
	<!--	$n_year , $_month , $n_day 변수는 date.php 에 있음.		-->
	<table border='0' cellpadding='0' cellspacing='0' align='left'>
	<tr>
	<td valign='top'>
		<table width='80' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--년도-->
			<div align='center'><font size='2'><input type='text' value='<? echo $n_year; ?>' name='year' maxlength='4' style='width=40;'>&nbsp;년</font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='65' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--월-->
			<div align='center'><font size='2'><input type='text' value='<? echo $n_month; ?>' name='month' maxlength='2' style='width=25;'>&nbsp;월</font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='65' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--일-->
			<div align='center'><font size='2'><input type='text' value='<? echo $n_day; ?>' name='day' maxlength='2' style='width=25;'>&nbsp;일</font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<!--금액-->
		<table border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='top'>
			<table width='100' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><input type='text' name='amount' maxlength='15' style='width:90; height:22;'></font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<table width='30' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'>원</font></div>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td>
	<!--옵션 사항-->
	<table border='0' cellpadding='0' cellspacing='0' align='left'>
	<tr>
	<td valign='top'>
		<table width='70' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--수입 지출-->
			<div align='center'>
			<select name='in_out' style='width=60;'>
				<option>수/지
				<option>--------
				<option value='in'>수입
				<option value='out'>지출
			</select>
			</div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='70' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--결제 수단-->
			<div align='center'>
			<select name='bank_cash' style='width=55;'>
				<option>수단
				<option>-------
				<option value='bank'>은행
				<option value='cash'>현금
			</select>
			</div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='70' height='30' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<!--사용 용도-->
			<div align='center'>
			<select name='uses' style='width=55;'>
				<option>용도
				<option>-------
				<option value='acc'>입금
				<option value='cont'>찬조
				<option value='etc'>기타
			</select>
			</div>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td valign='top'>
	<table border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='top'>
		<table border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='top'>
			<!--간단한 내역-->
			<table height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='bottom'>
				<table width='80' height='17' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>
				<td valign='top'>
					<div align='center'><font size='2'>간단 내역&nbsp;:&nbsp;</font></div>
				</td>
				</tr>
				</table>
			</td>
			<td valign='top'>
				<table width='200' height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>
				<td valign='bottom'>
					<div align='center'><font size='2'><input type='text' name='simple' maxlength='20' style='width:190; height:20;'></font></div>
				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		<tr>
		<td valign='top'>
			<!--상세 내역-->
			<table border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='top'>
				<table width='80' height='70' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>
				<td valign='middle'>
					<div align='center'><font size='2'>상세 내역&nbsp;:&nbsp</font></div>
				</td>
				</tr>
				</table>
			</td>
			<td valign='top'>
				<table width='200' height='70' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr>
				<td valign='middle'>
					<div align='center'><font size='2'>
					<textarea rows='4' cols='24' name='detail' wrap='physical'></textarea>
					</font></div>
				</td>
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='60' height='95' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'>
			<input type='button' onclick='insert_test()' value='입력' style='width:50; height:70;'>
			</font></div>
		</td>
		</tr>
		</table>	
	</td>
	</tr>
	</table>
</td>
</tr>
</form>
</table>