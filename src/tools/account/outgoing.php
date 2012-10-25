<?

$year=htmlspecialchars($_POST['year']);
$month=htmlspecialchars($_POST['month']);

	if($year==0)
		$year=$n_year;
	if($month<1 || $mont>12)
		$month=$n_month;

$time=mktime(0,0,0,$month,1,$year);
$t_time=mktime(0,0,0,$month+1,1,$year);

$select_out=mysql_query("select * from account where date>=$time and date<$t_time and in_out='out' order by date asc", $dbconnect);
$sum_out=mysql_query("select sum(amount) from account where date>=$time and date<$t_time and in_out='out'", $dbconnect);

$re_sum_out=mysql_fetch_row($sum_out);

$num_out=mysql_num_rows($select_out);

?>

<table border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td valign='top'>
	<!--상단-->
	<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/1.jpg'>
	<tr>
	<td valign='top'>
		<table width='350' height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='left'><font size='2'>&nbsp;&nbsp;지출 OUTGOING</font><div>
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
			<table width='95' height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'>날짜</font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<table width='160' height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'>내역</font></div>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<table width='95' height='25' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='middle'>
				<div align='center'><font size='2'>금액</font></div>
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
<td valign='top'>
	<!--중단-->
	<?
	if($num_out==0)
	{
	?>
	<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/2.jpg'>
	<tr>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2' color='dd0000'>내역이</font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='160' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2' color='dd0000'>없습니다.</font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2' color='dd0000'>^.^</font></div>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	<?
	}
	?>
	<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/2.jpg'>
		<?	while($result_out=mysql_fetch_row($select_out))	{		//반복문을 사용하여 내역을 표시 합니다.	?>
	<tr>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'><? echo date("Y/n/j", $result_out[1]);	//날짜 ?></font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='160' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='left'><font size='2'>&nbsp;&nbsp;&nbsp;<? echo $result_out[2];	//내역 ?></font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'><? echo (int)$result_out[5];	//금액 ?>&nbsp;원</font></div>
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
	<table width='350' height='50' border='0' cellpadding='0' cellspacing='0' align='center' background='./images/3.jpg'>
	<tr>
	<td valign='middle'>
		<div align='right'><font size='3'>총금액 : <? echo (int)$re_sum_out[0]	//월 합계 ?>&nbsp;원&nbsp;&nbsp;&nbsp;<!--그림으로 교체--></font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>