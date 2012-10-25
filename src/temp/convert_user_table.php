<?
// convert-_-/
exit; // 후후, 고쳐야 되지롱~

// 실행한 뒤에는 꼭 jaram_groups_spec.sql 을 실행하시오~
// LAST WORK - 전체 유저 다시 컨버트 ㅡ_ㅡ; 젠장;
$dbcon = mysql_connect("localhost", "webmaster", "vnfjdqhsh");
mysql_select_db("jaram", $dbcon) or die("jaram 데이터 베이스 연결에 실패했습니다.");
$result = mysql_query("SELECT * FROM member WHERE level < 7 OR (level = 7 AND number='19기') ORDER BY id");
mysql_close($dbcon);

$dbcon = mysql_connect("localhost", "webteam", "tpfmwlgh");
mysql_select_db("neojaram", $dbcon) or die("neojaram 데이터 베이스 연결에 실패했습니다.");;
mysql_query("DELETE FROM jaram_users");
mysql_query("DELETE FROM jaram_groups");
mysql_query("DELETE FROM jaram_user_group");

?>
<h1>jaram/member to<br> neojaram/jaram_users & neojaram/jaram_groups</h1>
<table width="100%" border="1">
	<tr>
		<td>uid</td>
		<td>아이디</td>
		<td>이름</td>
		<td>기수</td>
	</tr>
<?
$uid = 1300;
while ($row = mysql_fetch_array($result)) {
	if (substr($row["userid"], 0, 1) == "_") continue;

	$res1 = mysql_query("INSERT INTO jaram_users (uid, user_id, user_name, user_password, user_number, user_email, user_homepage, user_phone1, user_phone2) VALUES ('".$uid."', '".$row["userid"]."', '".$row["name"]."', '".$row["passwd"]."', '".$row["number"]."', '".$row["email"]."', '".$row["homepage"]."', '".$row["phone1"]."', '".$row["phone2"]."')");
	$res2 = mysql_query("INSERT INTO jaram_groups (gid, group_name) VALUES ('".$uid."', '".$row["name"]."')");
	$res3 = mysql_query("INSERT INTO jaram_user_group (gid, uid) VALUES ('$uid', '$uid')");
	// $row["number"] + 1999 -> see jaram_groups_spec.sql
	$res4 = mysql_query("INSERT INTO jaram_user_group (gid, uid) VALUES ('".($row["number"] + 1199)."', '$uid')");
	if ($res1 && $res2) {
		?>
		<tr>
			<td><?=$uid?></td>
			<td><?=$row["userid"]?></td>
			<td><?=$row["name"]?></td>
			<td><?=$row["number"]?></td>
		</tr>
		<?
	}
	$uid++;
}
?>
</table>
<?
mysql_close($dbcon);
?>