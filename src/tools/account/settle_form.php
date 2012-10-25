<?

$date=mktime(0,0,0,$month,15,$year);		//income.php 과 outgoing.php 에서 받았다.
$select_date=mysql_query("select date from bank where date='$date'", $dbconnect);
$past_date=mysql_fetch_row($select_date);

$select_bank=mysql_query("select * from bank order by date desc", $dbconnect);

$num_bank=mysql_num_rows($select_bank);

?>

<script language='javascript'>
<!--
	function settle_test()
	{
		var year=document.settle.year.value;
		var month=document.settle.month.value;
		var past_date=document.settle.past_date.value;
		var date=document.settle.date.value;
		var n_year=document.settle.n_year.value;
		var n_month=document.settle.n_month.value;


		if(past_date==date)
			alert(year + "년" + month + "월의 정산은 이미 처리 되었습니다.");
		else if(year>n_year)
			alert("아직은 " + year + " 년이 아닙니다.");
		else if(month>n_month)
			alert("아직은 " + month + " 월이 아닙니다.");
		else
		{
			flag=confirm("다시 수정할 수 없으니 신중히 선택하여 주십시오. 월말 정산 하시겠습니까?");
			if(flag==true)
				document.settle.submit();
		}
	}

	function autoo()
	{
		var auto=document.auto.auto.value;
		var num=document.auto.num_bank.value;

		if(auto=='')
			alert("선택해주십시오.");
		else if(auto=='자동')
		{
			if(num<1)
				alert("최소 한달은 스스로 해야 합니다.");
			else
				document.auto.submit();
		}
		else if(auto=='수동')
			alert("이미 수동 모드 입니다.");
	}
-->
</script>

<!--월만 정산-->
<table border='1' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td valign='top'>
	<table width='360' border='1' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='middle'>
		<table width='240' border='1' cellpadding='0' cellspacing='0' align='center'>
		<form action='./auto.php' method='post' name='auto'>
		<tr>
		<td>
			<div align='center'><font size='2'>
			<select name='auto' style='width:51'>
				<option>------
				<option value='자동'>자동
				<option value='수동'>수동
			</font></div>
		</td>
		<td>
			<div align='center'><font size='2'>
			<input type='hidden' name='num_bank' value='<? echo (int)$num_bank; ?>'>
			<input type='button' onclick='autoo()' value='자동 수동 선택' style='width:100'>
			</font></div>
		</td>
		</tr>
		</form>
		</table>
	</td>
	<td valign='top'>
		<table width='120' border='1' cellpadding='0' cellspacing='0' align='center'>
		<form action='./settle.php' method='get' name='settle'>
		<tr>
		<td>		
			<div align='right'><font size='2'>
			<!--date.php 파일에 있는 변수-->
			<input type='hidden' name='year' value='<? echo $year; ?>'>
			<input type='hidden' name='n_year' value='<? echo $n_year; ?>'>
			<input type='hidden' name='month' value='<? echo $month; ?>'>
			<input type='hidden' name='n_month' value='<? echo $n_month; ?>'>
			<!--account.php 파일에 있는 변수-->
			<input type='hidden' name='sum' value='<? echo $sum; ?>'>
			<input type='hidden' name='cash' value='<? echo $cash; ?>'>
			<input type='hidden' name='bank' value='<? echo $bank; ?>'>
			<input type='hidden' name='date' value='<? echo $date; ?>'>
			<input type='hidden' name='past_date' value='<? echo $past_date[0]; ?>'>
			<input type='button' onclick='settle_test()' value='월말 정산 하기' style='width:100; height:25;'>&nbsp;&nbsp;
			</font></div>
		</td>
		</tr>
		</form>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td valign='top'>
	<!--월별 정산 내역-->
	<table border='1' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='top'>
		<!--상단-->
		<table width='360' height='50' border='1' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='left'><font size='2'>&nbsp;&nbsp;<b>|</b>&nbsp;월말 정산</font></div>
		</td>
		</tr>
		<tr>
		<td valign='top'>
			<div align='center'><font size='2'>날짜&nbsp;&nbsp;현금&nbsp;&nbsp;은행&nbsp;&nbsp;합계&nbsp;&nbsp;기타</font></div>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--중단-->
		<table width='360' border='1' cellpadding='0' cellspacing='0' align='center'>
		<?	while($result=mysql_fetch_row($select_bank))	{		//반복문을 사용하여 정산 내역을 표시 합니다.	?>
		<tr>
		<td valign='top'>
			<!--날짜-->
			<table width='60' height='20' border='1' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><? echo date("Y/n", $result[1]); ?></font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--은행-->
			<table width='80' height='20' border='1' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><? echo $result[2]; ?></font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--현금-->
			<table width='80' height='20' border='1' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><? echo $result[3]; ?></font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--합계-->
			<table width='90' height='20' border='1' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><? echo $result[4]; ?></font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--기타-->
			<table width='50' height='20' border='1' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'><? echo $result[5]; ?></font></div>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		<?	}	?>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--하단-->
		<table width='360' height='50' border='1' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'>1 2 3 4 5 6 ...</font></div>
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
</table>