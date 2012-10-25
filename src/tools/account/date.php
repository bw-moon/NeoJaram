<?
	//현재 날짜 추출
	$n_year=date("Y", time());
	$n_month=date("n", time());
	$n_day=date("j", time());


	$year=htmlspecialchars($_POST['year']);
	$month=htmlspecialchars($_POST['month']);


	if($year==0)
		$year=$n_year;
	if($month<1 || $mont>12)
		$month=$n_month;

?>
<script language='javascript'>
<!--
	function date_test()
	{
		var year=document.date.year.value;
		var month=document.date.month.value;
		

		if(year=='')
			alert("어머? 년도를 안쓰셨나봐요? ^^");
		else if(month<1 || month>12)
			alert("에이~ 정확한 월을 입력해주세요~ ^^");
		else if(month=='')
			alert("빈칸은 정중히 사양하겠습니다. ^^");
		else
			date.submit();
	}
-->
</script>
<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/date.jpg'>
<tr>
<td valign='top'>
	<!--오른쪽 테이블-->
	<table border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='top'>
		<!--date 제목-->
		<table width='260' height='42' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='left'><font size='3'>&nbsp;&nbsp;&nbsp;<b>|</b>&nbsp;&nbsp;LIST OF DATES</font></div>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--폼 태그-->
		<table border='0' cellpadding='0' cellspacing='0' align='left'>
		<form action='./' method='post' name='date'>
		<tr>
		<td valign='middle'>
			<!--년도-->
			<table width='70' height='48' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td>
				<div align='center'><font size='2'>&nbsp;&nbsp;<input type='text' value='<? echo $year; ?>' name='year' maxlength='4' style='width=40;'>&nbsp;년</font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='middle'>
			<!--월-->
			<table width='50' height='48' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td>
				<div align='center'><font size='2'><input type='text' value='<? echo $month; ?>' name='month' maxlength='2' style='width=25;'>&nbsp;월</font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='middle'>
			<!--쿼리전송-->
			<table width='100' height='48' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td>
				<div align='center'>
				<input type='button' onclick='date_test()' value='월별 검색' style='width:80; height:20;' border='0'>
				</div>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</form>
		</table>
	</td>
	</tr>
	</table>
</td>
<td valign='top'>
	<!--왼쪽 테이블-->
	<table width='90' border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='top'>
		<!--Month-->
		<table width='80' height='30' border='0' cellpadding='0' cellspacing='0' align='left'>
		<form action='./down_month.php' method='post' name='down_month'>
		<tr>
		<td valign='middle'>
			<input type='hidden' name='year' value='<? echo $year; ?>'>
			<input type='hidden' name='month' value='<? echo $month; ?>'>
			<div align='center' onclick='down_month.submit()' style="cursor:hand;"><font size='1'>◀◀</font></div>
		</td>
		</form>
		<td valign='middle'>
			<div align='center'><font size='2'>Month</font></div>
		</td>
		<form action='./up_month.php' method='post' name='up_month'>
		<td valign='middle'>
			<input type='hidden' name='year' value='<? echo $year; ?>'>
			<input type='hidden' name='month' value='<? echo $month; ?>'>
			<div align='center' onclick='up_month.submit()' style="cursor:hand;"><font size='1'>▶▶</font></div>
		</td>
		</tr>
		</form>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--월 표시-->
		<table width='80' height='60' border='0' cellpadding='0' cellspacing='0' align='left'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='7' color='ef0000'><? echo $month; ?></font><div>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
