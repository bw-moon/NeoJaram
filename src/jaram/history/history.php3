<?
/*
 * Jaram > History
 * by 21th 조요한
 */
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Jaram - Hanyang University Software Study Group</title>
</head>
<body>
<? 
	dbconn();
	$result_year=mysql_query("select year from jaram_history");
	
	//입력된 연도를 year[]배열에 넣음.
	$i=0;
	while($row=mysql_fetch_array($result_year))
	{
		$same=0;
		for($j=0; $j<sizeof($year); $j++)
		{
			if($year[$j]==$row[year]){
				$same=1;
			}
		}
		if($same==0){
			$year[$i]=$row[year];
			$i++;
		}
				
	}
	//year 오름차순정렬
	for ($i = 0; $i < sizeof($year)-1 ; $i++) {
		for( $j = $i+1 ; $j < sizeof($year) ; $j++) {
			if($year[$i] > $year[$j]) {
			$temp = $year[$i];
			$year[$i] = $year[$j];
			$year[$j] = $temp;

			}
		}
	}
?>
<table cellpadding="0" cellspacing="0" width="90%">
    <tr>
        <td width="90%" height="100%" valign="top">
 <?    
	for ($i = 0; $i < sizeof($year); $i++) {
		$result_month=mysql_query("select month,contents from jaram_history where year='$year[$i]' order by month");

		echo"<table cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">";
              
		echo"		<tr>";
        echo"			 <td width=\"100%\" bgcolor=\"#EEEEEE\" height=\"22\" colspan=\"2\">";
        echo"			     <p>&nbsp;<img src=\"blt_content_d1.gif\" width=\"9\" height=\"9\" border=\"0\"> $year[$i] 년</p>";
        echo"			 </td>";
        echo"    </tr>"           ;
		
			while($row_month=mysql_fetch_array($result_month)){
				echo"		<tr>";
				echo"          <td width=\"8%\" height=\"20\" valign=\"top\">";
				echo"               <p align=\"right\"> $row_month[month] 월</p>";
				echo"          </td>";
				echo"          <td width=\"92%\" height=\"20\">";
				echo"                <p>$row_month[contents]&nbsp;&nbsp;";
				if(chk_auth(auth_view)){
					echo"                     <a href=\"delete.php3?year=$year[$i]&month=$row_month[month]\">Del</a>";
				}
				echo"                 </p>";
				echo"          </td>";
				echo"     </tr>";
				echo"     <tr>";
				echo"         <td width=\"8%\" bgcolor=\"white\" height=\"1\">";
				echo"         </td>";
				echo"         <td width=\"92%\" bgcolor=\"#CCCCCC\" height=\"1\">";
				echo"         </td>";
				echo"     </tr>";
			}
        echo"    </table>";
	}
?>

        </td>
    </tr>
</table>
<?if(chk_auth(auth_delete)){?>
		<form method='post' action=insert.php3>             
				<br/>연도<input type='text' name='year' size="12">&nbsp;&nbsp;
				월<input type='text' name='month' size="12"><br />내용<br />
				<textarea name='contents' cols="39" rows=5></textarea>	
				<input type='submit' value='글쓰기'></font>
		</form>
<? } ?>
</body>
</html>

