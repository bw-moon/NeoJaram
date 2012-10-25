<?php

?>
<table width="100%" cellspacing="7" cellpadding="0" border="0" class="text">
<tr>
	<td colspan="4"><b>Comments</b></td>
</tr>
<tr>
	<td colspan="4" height="1" bgcolor="#efefef"></td>
</tr>
<?
while ($cdata = mysql_fetch_assoc($result))
{
	
	if ($cdata['group_name'])
	{
		$name = "<a href=\"/aboutjaram/memberinfo/?gid=$cdata[user_id]\" title=\"삭제\">".$cdata['group_name']."</a>";
	}
	else 
		$name = $cdata['name'];
	
	$date = date("Y/m/d", $cdata['reg_date']);
	//$cdata['text'] = strip_tags($cdata['text'], '
	$cdata['text'] = htmlspecialchars($cdata['text'], ENT_QUOTES);
	$cdata['text'] = realsafehtml($cdata['text']);
	$cdata['text'] = auto_link($cdata['text']);
	$cdata['text'] = nl2br($cdata['text']);
	

echo ("<tr>
	<td width=\"50\" valign=\"top\">$name</td>
	<td width=\"5\" bgcolor=\"#efefef\"></td>
	<td>$cdata[text]</td>
	<td width=\"60\" align=\"right\" valign=\"top\"><a href=\"/studyzone/seminar/delete.php?comment_id=$cdata[comment_id]\">$date</a></td>
</tr>");
}
?>

<tr>
	<td colspan="4" height="1" bgcolor="#efefef"></td>
</tr>
</table>
<?}?>