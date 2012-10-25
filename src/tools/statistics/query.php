<?
/****************************************************************
 * PHP Standard Comment													
 *	
 * Filename : query.php
 * 
 * Last Update : 
 ****************************************************************/

	// 안쓸때는 꺼놓자 -0-;;
	exit();

   include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");


    dbconn();

	$query = $_POST['query'];
	$query = stripcslashes($query);
	$sub_query = explode(" ", $query);

?>
<h3>MYSQL Query</h3>
<form name="myForm" action="query.php" method="post">
<input type="text" size="80" name="query" value="<?=$query?>">
<input type="button" value="Submit" onclick="document.myForm.submit();">
</form>
<?
	if( $query )
	{
		if($sub_query[0] == "select" || $sub_query[0] == "desc" || $sub_query[0] == "show" || $sub_query[0] == "explain" )
		{
			echo "<table align=center style=\"padding:4 2 4 2;margin: 0 0 0 0;border:solid 0;\">\n";

			$first_time = time();
			$result = mysql_query($query);
			$result_time = time() - $first_time;

			$field_count = mysql_num_fields($result);
			$row_count = mysql_num_rows($result);
			if(!$field_count) {
				$errNO = mysql_errno();
				$errMSG = mysql_error();
				echo("Error Code  $errNO  :  $errMSG");
				exit;
			}

			echo "<tr><td colspan=${field_count}>query : " . $query . "</td></tr>\n\n";

			// column name
			echo "<tr>\n";
			for( $i = 0; $i < $field_count; $i++)
				echo "<td style='font-weight:bold;'>" . mysql_field_name($result, $i) . "</td>\n";
			echo "</tr>\n\n";

			echo "<tr><td colspan=${field_count} style=\"border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#F5F5F5;\"></td></tr>\n";
			echo "<!-- content -->\n";

			while( $field = mysql_fetch_array($result))
			{
				echo "<tr>";
				for( $i = 0; $i < $field_count; $i++)
				{
					echo "<td>" . $field[$i] . "</td>";
				}
				echo "</tr>";
			}
			echo "\n\n<tr><td colspan=${field_count} style='font-weight:bold;'>" . $row_count . " rows selected(" . $result_time . " sec)</td></tr>\n\n";
			echo "\n<!-- End of content -->\n</table>\n";
		}
		else
		{
			$result = mysql_query($query);
			$affected_count = mysql_affected_rows();
			if(!$result) {
				$errNO = mysql_errno();
				$errMSG = mysql_error();
				echo("Error Code  $errNO  :  $errMSG");
				exit;
			}
			else
			{
				echo "<span style=\"font-family:Vredana; font-size:9pt;\">" . $query . "</span>";
				echo "<br><span style=\"font-family:Vredana; font-size:9pt;\">success</span>";
				echo "<br><span style=\"font-family:Vredana; font-size:9pt;\">$affected_count rows affected!</span>";
			}
		}
	}

?>

<script language="javascript" type="text/javascript">
	myForm.query.focus();
</script>

