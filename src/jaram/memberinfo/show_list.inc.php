<?
$line_link = array();
foreach ($result as $rows) {
	if (!isset($line_link[$rows['user_number']])) {
		$line_link[$rows['user_number']] = sprintf("<a href=\"#%s\">%s기</a>", $rows['user_number'], $rows['user_number']);
	}
}
?>
<p>
<img src="<?=WEB_ABS_PATH?>/images/icons/link.gif"/> 바로가기 : <?=implode(" | ", $line_link)?>
</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="fancy_table">
<thead>
	<tr>
		<th class="title_left" width="100">Real Name</th>
		<?
		if (!empty($_SESSION["jaram_user_id"])) {
			print "\t\t<th>Login Name</th>\n";
			$colspan = 4;
		} else {
			$colspan = 3;
		}
		?>
		<th>Email Address</th>
		<th  class="title_right" width="200">Homepage</th>
	</tr>
</thead>
<tbody>
	<?
	$num_old = 0;
	$num_array = array();
	foreach ($result as $rows) {
		$rows["user_homepage"] = show_homepage($rows["user_homepage"]);

		$show_homepage = "";
		if (!empty($rows["user_homepage"])) {
			$show_homepage = "<a href=\"http://".$rows["user_homepage"]."\" class=\"sub\">http://".$rows["user_homepage"]."</a>";
		}

		if ($num_old != $rows["user_number"]) {
			$num_array[] = $rows["user_number"];
			print "\t<tr>\n";
			print "\t\t<td colspan=\"".$colspan."\" class=\"sub_title\"><h3><a name=\"".$rows["user_number"]."\">".$rows["user_number"]."기 <a href=\"#\" class=\"sub\">[top]</a></a></h3></td>\n";
			print "\t</tr>\n";
			$num_old = $rows["user_number"];
		}

		print "\t<tr>\n";
				
		print "\t\t<td align=\"center\"><a href=\"".WEB_ABS_PATH."/jaram/memberinfo/?gid=".$rows["uid"]."\">".$rows["user_name"]."</a></td>\n";
		if ($_SESSION["jaram_user_id"]) print "\t<td align=\"center\" class=\"sub\"><a href=\"".WEB_ABS_PATH."/jaram/memberinfo/?gid=".$rows["uid"]."\">".$rows["user_id"]."</a></td>\n";
		print "\t\t<td>".show_email($rows["uid"],$rows["user_email"])."</td>\n";
		print "\t\t<td>".$show_homepage."</td>\n";

		print "\t</tr>\n";
	}
echo "</tbody></table>";
?>