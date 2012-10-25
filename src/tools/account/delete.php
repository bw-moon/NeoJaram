<?

include ('./db_conn.php');


$select_a=mysql_query("select * from account", $dbconnect);
$select_b=mysql_query("select * from bank", $dbconnect);

$num_a=mysql_num_fields($select_a);
$num_b=mysql_num_fields($select_b);
?>
<table border='0'>
<tr>
<td valign='top'>
<?
echo	"<table border='1'>";
while($result_a=mysql_fetch_row($select_a))
{
echo	"<tr>";
	for($i=0; $i<$num_a; $i++)
	{
echo	"<td>";
			echo $result_a[$i] . "&nbsp;";
echo	"</td>";
	}
echo	"<td><a href='./del.php?no_1=$result_a[0]'>지우기</a></td>";
echo	"</tr>";
}
echo	"</table>";
?>
</td>
<td>
<?
echo	"<table border='1'>";
while($result_b=mysql_fetch_row($select_b))
{
echo	"<tr>";
	for($i=0; $i<$num_b; $i++)
	{
echo	"<td>";
			echo $result_b[$i] . "&nbsp;";
echo	"</td>";
	}
echo	"<td><a href='./del.php?no_2=$result_b[0]'>지우기</a></td>";
echo	"</tr>";
}
echo	"</table>";
?>
</td>
</tr>
</table>
<?
mysql_close($dbconnect);
?>