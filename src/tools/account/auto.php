<?

include ('./db_conn.php');

$auto=htmlspecialchars($_POST['auto']);

mysql_query("update auto set auto='$auto' where no='1'", $dbconnect);

mysql_close($dbconnect);

header("location: ./");

?>