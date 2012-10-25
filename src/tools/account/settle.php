<?

include ('./db_conn.php');

$year=htmlspecialchars($_GET['year']);
$month=htmlspecialchars($_GET['month']);

//저장될 변수들
$date=mktime(0,0,0,$month,15,$year);
$sum=htmlspecialchars($_GET['sum']);
$cash=htmlspecialchars($_GET['cash']);
$bank=htmlspecialchars($_GET['bank']);
$advice=" ";


$select=mysql_query("select sum from bank where date<'$date' order by date desc", $dbconnect);		//전달의 sum과 date 구하기
$s_sum=mysql_fetch_row($select);



if($sum>$s_sum[0])
	$advice = 흑자;
else if($sum==$s_sum[0])
	$advice = 유지;
else if($sum<$s_sum[0])
	$advice = 적자;


mysql_query("insert into bank (date, cash, bank, sum, advice) values ('$date', '$cash', '$bank', '$sum', '$advice')", $dbconnect);

mysql_close($dbconnect);

header('location: ./');

?>