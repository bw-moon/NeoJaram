<?

include ('./db_conn.php');

$no_1=htmlspecialchars($_GET['no_1']);

mysql_query("delete from account where no='$no_1'", $dbconnect);


$no_2=htmlspecialchars($_GET['no_2']);

mysql_query("delete from bank where no='$no_2'", $dbconnect);


mysql_close($dbconnect);

header("location: ./delete.php");
?>