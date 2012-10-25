<?

$date=mktime(0,0,0,$month,15,$year);		//income.php 과 outgoing.php 에서 받았다.
$select_date=mysql_query("select date from bank where date='$date'", $dbconnect);
$past_date=mysql_fetch_row($select_date);

$select_bank=mysql_query("select * from bank order by date desc", $dbconnect);

$num_bank=mysql_num_rows($select_bank);

?>

<script language='javascript'>
<!--
	function autoo()
	{
		var auto=document.auto.auto.value;
		var num=document.auto.num_bank.value;

		if(auto=='')
			alert("선택해주십시오.");
		else if(auto=='자동')
			alert("이미 자동 모드 입니다.");
		else if(auto=='수동')
			document.auto.submit();
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
		<table width='240' height='27' border='1' cellpadding='0' cellspacing='0' align='left'>
		<form action='./auto.php' method='post' name='auto'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'>
			<select name='auto' style='width:51'>
				<option>------
				<option value='자동'>자동
				<option value='수동'>수동
			</font></div>
		</td>
		<td valign='middle'>
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
		<table width='120' height='27' border='1' cellpadding='0' cellspacing='0' align='center'>
		<form action='./settle.php' method='get' name='settle'>
		<tr>
		<td valign='middle'>		
			<div align='center'><font color='red' size='2'><b><strong>자동모드</strong></b></font></div>
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