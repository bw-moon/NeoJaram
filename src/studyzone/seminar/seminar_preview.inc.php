<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<!-- <tr>
	<td class="a18b"><font color="#000099"><?=$_POST['seminar_topic']?></font><br/><br/></td>
</tr>
<tr>
	<td class="a12b"><font color="#000099">OVERVIEW</font></td>
</tr>
<tr>
	<td><?=nl2br($_POST['seminar_desc'])?></td>
</tr> -->
<tr>
	<td class="a12b"><font color="#000099">THIS SEMINAR INCLUDES THE FOLLOWING TOPICS</font></td>
</tr>
<tr>
	<td><?
	$text = $_POST['seminar_topics'];
	include "seminar_formatter.php";
	?></td>
</tr>
<!-- <tr>
	<td class="a12b"></br><font color="#000099">BENEFITS OF ATTENDING</font></td>
</tr>
<tr>
	<td><?=nl2br($_POST['seminar_benefit'])?></td>
</tr> -->
</table>
