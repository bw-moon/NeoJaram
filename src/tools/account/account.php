<?

$year=htmlspecialchars($_POST['year']);
$month=htmlspecialchars($_POST['month']);

	if($year==0)
		$year=$n_year;
	if((int)$month<1 || $mont>12)
		$month=$n_month;



/////////////////////////////////////////자동화기능/////////////////////////////////////////
/*
$select_date_test=mysql_query("select date from bank order by date desc", $dbconnect);
$result_date=mysql_fetch_row($select_date_test);

$now_year=date("Y", time());
$now_month=date("n", time());
$past_year=date("Y", $result_date[0]);
$past_month=date("n", $result_date[0]);

if($now_year!=$past_year || $now_month-$past_month>1)
{
	$year=$past_year;
	$month=$past_month+1;
}
*/
/////////////////////////////////////////자동화기능/////////////////////////////////////////


$time=mktime(0,0,0,$month+1,1,$year);


$bank__in=mysql_query("select sum(amount) from account where date<'$time' and in_out='in' and bank_cash='bank'", $dbconnect);
$bank__out=mysql_query("select sum(amount) from account where date<'$time' and in_out='out' and bank_cash='bank'", $dbconnect);
$bank_in=mysql_fetch_row($bank__in);
$bank_out=mysql_fetch_row($bank__out);
(int)$bank=(int)$bank_in[0] - (int)$bank_out[0];
//은행의 월별 수입지출의 합계를 구합니다.


$cash__in=mysql_query("select sum(amount) from account where date<$time and in_out='in' and bank_cash='cash'", $dbconnect);
$cash__out=mysql_query("select sum(amount) from account where date<$time and in_out='out' and bank_cash='cash'", $dbconnect);
$cash_in=mysql_fetch_row($cash__in);
$cash_out=mysql_fetch_row($cash__out);
(int)$cash=(int)$cash_in[0] - (int)$cash_out[0];
//현금의 월별 수입지출의 합계를 구합니다.


$sum=$bank + $cash;

//월별 총 잔액을 검색합니다.

?>

<table border='0' cellpadding='0' cellspacing='0' align='center' background='./images/account.jpg'>
<tr>
<td valign='top'>
	<!--총 금액-->
	<table width='350' height='38' border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='middle'>
		<div align='left'><font size='2'>&nbsp;&nbsp;월 총 잔액</font></div>
	</td>
	<td valign='middle'>
		<div align='right'><font size='2'><? echo (int)$sum; ?>&nbsp;원&nbsp;</font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td valign='top'>
	<!--현금 잔액-->
	<table width='350' height='26' border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='middle'>
		<div align='left'><font size='2'>&nbsp;&nbsp;현금 잔액 CASH<!--그림으로 교체--></font></div>
	</td>
	<td valign='middle'>
		<div align='right'><font size='2'><? echo (int)$cash; ?>&nbsp;원&nbsp;</font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td valign='top'>
	<!--은행 잔고-->
	<table width='350' height='26' border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='middle'>
		<div align='left'><font size='2'>&nbsp;&nbsp;은행 잔고 BANK<!--그림으로 교체--></font></div>
	</td>
	<td valign='middle'>
		<div align='right'><font size='2'><? echo (int)$bank; ?>&nbsp;원&nbsp;</font></div>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>


<?
/////////////////////////////////////////자동화기능/////////////////////////////////////////
/*

if($now_year!=$past_year || $now_month-$past_month>1)
	echo "<meta http-equiv='Refresh' Content='0; url=./settle.php?year=$year&month=$month&sum=$sum&cash=$cash&bank=$bank'>";

*/
/////////////////////////////////////////자동화기능/////////////////////////////////////////
?>