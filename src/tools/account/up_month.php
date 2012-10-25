<?

$year=htmlspecialchars($_POST['year']);
$month=htmlspecialchars($_POST['month']);


if($month==12)
{
	$month=1;
	$year=$year+1;
}
else
	$month=$month+1;

?>
<html>
<head>
<title>

</title>
</head>
<body onload="document.month.submit()">
	<form action='./' method='post' name='month'>
	<input type='hidden' name='year' value='<? echo $year; ?>'>
	<input type='hidden' name='month' value='<? echo $month; ?>'>
	</form>
</body>
</html>