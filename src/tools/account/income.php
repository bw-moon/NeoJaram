<?

$year=htmlspecialchars($_POST['year']);
$month=htmlspecialchars($_POST['month']);

	if($year==0)
		$year=$n_year;
	if($month<1 || $mont>12)
		$month=$n_month;

$time=mktime(0,0,0,$month,1,$year);		//월 초
$t_time=mktime(0,0,0,$month+1,1,$year);		//월 말

$select_in=mysql_query("select * from account where date>=$time and date<$t_time and in_out='in' order by date asc", $dbconnect);
//월 별로 수입에 대한 자료를 검색합니다.

$sum_in=mysql_query("select sum(amount) from account where date>=$time and date<$t_time and in_out='in'", $dbconnect);
//월 합계를 검색합니다.

$re_sum_in=mysql_fetch_row($sum_in);

$num_in=mysql_num_rows($select_in);

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
			<div align='left'><font size='2'>&nbsp;&nbsp;수입 INCOME</font><div>
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
	if($num_in==0)
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
		<?	while($result_in=mysql_fetch_row($select_in))	{		//반복문을 사용하여 내역을 표시 합니다.	?>
	<tr>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'><? echo date("Y/n/j", $result_in[1]);	//날짜 ?></font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='160' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='left'><font size='2'>&nbsp;&nbsp;&nbsp;<? echo $result_in[2];	//내역 ?></font></div>
		</td>
		</tr>
		</table>
	</td>
	<td valign='top'>
		<table width='95' height='28' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='middle'>
			<div align='center'><font size='2'><? echo (int)$result_in[5];	//금액 ?>&nbsp;원</font></div>
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
		<div align='right'><font size='3'>총금액 : <? echo (int)$re_sum_in[0]	//월 합계 ?>&nbsp;원&nbsp;&nbsp;&nbsp;</font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>