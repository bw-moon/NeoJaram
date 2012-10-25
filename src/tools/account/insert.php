<?
include ('./db_conn.php');

$year=htmlspecialchars($_POST['year']);
$month=htmlspecialchars($_POST['month']);
$day=htmlspecialchars($_POST['day']);

$date=mktime(0,0,0,$month,$day,$year);	//변수로 받은 날짜를 이용하여 유닉스 시간으로 바꿈.

$in_out=htmlspecialchars($_POST['in_out']);
$bank_cash=htmlspecialchars($_POST['bank_cash']);
$uses=htmlspecialchars($_POST['uses']);
$amount=htmlspecialchars($_POST['amount']);
$simple=htmlspecialchars($_POST['simple']);
$detail=htmlspecialchars($_POST['detail']);
//변수 받기


mysql_query("insert into account (date, simple, detail, in_out, amount, bank_cash, uses) values ('$date', '$simple', '$detail', '$in_out', '$amount', '$bank_cash', '$uses')", $dbconnect);

mysql_close($dbconnect);

header("location: ./");

}
?>