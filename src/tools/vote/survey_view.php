<?php
/*************************************
 *			view_survey.php						*
 *			@decription : survey view 		*
 *			@author : serue						*
 *************************************/

############## header ################
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");
include "./lib/function.php";			// function include
include "./lib/admin_conf.php";	// admin config include

// 데이터베이스 접속
dbconn();


############## body ################
$table = "jaram_vote";
$query = "SELECT a.vote_id, a.topic_text, a.topic_comment, a.vote_start, a.vote_limit, a.is_open, b.uid, b.user_name FROM jaram_vote AS a RIGHT JOIN jaram_users AS b ON a.user_uid = b.uid WHERE a.vote_id='".$_GET['vote_id']."' LIMIT 1";

$result = mysql_query($query);

$data = mysql_fetch_array($result);

if ( $data[is_open] != "on" ) {
	if (!isset($_SESSION['jaram_user_uid'])) {
		show_error_msg("비공개 투표입니다", "back");
	}
}

if (isset($_SESSION)) {
	$result = mysql_query("SELECT result_id FROM jaram_vote_result WHERE vote_id='".$data['vote_id']."' AND user_uid='".$_SESSION['jaram_user_uid']."' LIMIT 1;");

	if (!empty($result)) {
		$VOTE_DONE = @mysql_num_rows($result);
	} else {
		$VOTE_DONE = 0;
	}
}

// 투표기간 체크
if ($data['vote_start'] < time() && $data['vote_limit'] < time()) {
	$VOTE_CLOSE = 1;
} else {
	$VOTE_CLOSE = 0;
}


if ($VOTE_CLOSE == 0) {
	$period = date("F j, Y",$data['vote_start'])." ∼ ".date("F j, Y",$data['vote_limit']);
} else {
	$period = "<font color=\"#999999\">".date("F j, Y",$data['vote_start'])." ∼ ".date("F j, Y",$data['vote_limit'])."</font> <font color=\"red\">(finished)</font>";
}


$result = mysql_query("SELECT * FROM jaram_vote_option WHERE vote_id='".$data['vote_id']."' ORDER BY vote_option_id ASC;");

$VOTE_OPTIONS = array();
$option = 0;
$total_voter = 0;

while ($ary = mysql_fetch_assoc($result))
{ 
	while (list($key,$val) = each($ary))
	{
		$VOTE_OPTIONS[$option][$key] = $val;
		if ($key == "option_result") {
			$total_voter += $val;
		}
	}
	$option++;
}

//print_r($VOTE_OPTIONS);
$data[topic_comment] = nl2br($data[topic_comment]);
$data[topic_comment] = stripslashes($data[topic_comment]);
?>
<!-- Topic -->
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr>
	<td class="a12b" colspan="2">No.<?=$data['vote_id']?></td>
</tr>
<tr>
	<td bgcolor="#555555" height="1" colspan="2"></td>
</tr>
<tr>
	<td colspan="2" bgcolor="#f5f5f5"><b>Name</b> : <a href="/jaram/memberinfo/?gid=<?=$data['uid']?>"><?=$data['user_name']?></a></td>
</tr> 
<tr>
	<td colspan="2" bgcolor="#f5f5f5"><b>Subject</b> : <?=$data['topic_text']?></td>
</tr>
<tr>
	<td colspan="2" bgcolor="#f5f5f5"><b>Period</b> : <?=$period?></td>
</tr>
<tr>
	<td bgcolor="#bbbbbb" height="1" colspan="2"></td>
</tr>
</table>
<br/>
<?
if ($VOTE_DONE == 1 && $VOTE_CLOSE == 0) {
	echo "<font color=\"#CC6600\">[<b>이미 투표하셨습니다</b>]</font><br/><br/>";
}

if ($VOTE_CLOSE == 1) {
	echo "<font color=\"#CC6600\">[<b>투표가 종료되었습니다</b>]</font><br/><br/>";
}

echo $data['topic_comment'];

if ($VOTE_CLOSE == 0 && $VOTE_DONE != 1) { 
//if (1) { 
?>
<form method="POST" action="submit_ok.php?action=vote&amp;vote_id=<?=$data[vote_id]?>&amp;page=<?=$_GET[page]?>&amp;article_num=<?=$_GET[article_num]?>">
<table border="0" cellpadding="3" cellspacing="0" width="100%" class="text">
	<tr><td colspan="2"><b>Vote please !</b></td></tr>
<?
$no = 0;	 // 번호 초기화
$total_voted_sum = 0;	// 총 투표인원수

$query_var_op = "SELECT * FROM jaram_vote_option WHERE vote_id='".$data[vote_id]."' ORDER BY vote_option_id";
$result_var_op = mysql_query($query_var_op);
$total_var_op = mysql_num_rows($result_var_op);

while ( $var_op = mysql_fetch_array($result_var_op) ) {
	
	$no++;
	$total_voted_sum = $var_op['option_result'] + $total_voted_sum;
	
	$checked = ($no == 1) ? "checked" : "";

	echo("
	<tr valign=\"top\">
		<td >$no. $var_op[option_text]</td>
		<td align=\"right\"><input type=\"radio\" name=\"vote_option_id\" value=\"$var_op[vote_option_id]\" $checked></td>
	</tr>
	");		
}	
?>
	<tr valign="top"><td align="right" colspan="2"><input type="submit" value="vote"></td></tr>
</table>
</form>
<? } else { ?><br/><br/>
<table border="0" cellpadding="3" cellspacing="0" width="100%" class="text">
<tr><td colspan="2"><b>Vote Result</b> (total : <b><?=$total_voter?></b>명)</td></tr>
<?

$var_op['option_text'] = stripslashes($var_op['option_text']);

for ($i = 0 ; $i < count($VOTE_OPTIONS); $i++) {
	$percent_size = (int)(($VOTE_OPTIONS[$i]['option_result']/$total_voter) * 100);

	echo("<tr valign=\"top\"><td>".$VOTE_OPTIONS[$i]['option_text']." (".$percent_size."%)</td></tr>
		<tr valign=\"top\">
			<td><img src=\"./img/vote_pic.gif\" width=\"".$percent_size."%\" height=\"15\" alt=\"".stripslashes($var_op['option_text'])."\"></td>
			<td width=\"60\" align=\"right\"><b>".$VOTE_OPTIONS[$i]['option_result']."</b>명</td>
		</tr>
	");
}
?>
</table>
<br/>
<? } ?>
<?
############ comment ##############

$query = "SELECT a.comment_id, a.comment, a.signdate, b.uid, b.user_name FROM jaram_vote_comment AS a LEFT JOIN jaram_users AS b ON a.user_uid=b.uid WHERE a.vote_id='".$data['vote_id']."' ORDER BY a.comment_id";
$result = mysql_query($query);
?>

<table border="0" cellpadding="3" cellspacing="0" width="100%" class="text">
<tr>
	<td colspan="3"><b>Comments</b></td>
</tr>
<?
while ($rows = mysql_fetch_array($result)) {
	$rows['comment'] = nl2br(stripslashes($rows['comment']));
	$comment_signdate = date("y-m-d", $rows['signdate']);
	$detail_signdate = date("Y년, m월(F), l, d일\nh:i:s A", $rows['signdate']);

	if( $_SESSION['jaram_user_uid'] == $rows['uid'] )
	{
		echo("
		<tr>
			<td width=\"50\" valign=\"top\"><a href=\"/jaram/memberinfo/?gid=".$rows['uid']."\">".$rows['user_name']."</td>
			<td>$rows[comment]</td>
			<td align=\"right\" valign=\"top\" width=\"60\" title=\"$detail_signdate\"><a href=\"/tools/vote/comment_del.php?id=".$rows['comment_id']."\">".$comment_signdate."</a></td>
		</tr>
		");
	}
	else
	{
		echo("
		<tr>
			<td width=\"50\" valign=\"top\"><a href=\"/jaram/memberinfo/?gid=".$rows['uid']."\">".$rows['user_name']."</td>
			<td>$rows[comment]</td>
			<td align=\"right\" valign=\"top\" width=\"60\" title=\"$detail_signdate\">".$comment_signdate."</td>
		</tr>
		");
	}
}
?>
</table>
<form method="POST" action="submit_ok.php">
<input type="hidden" name="action" value="comment"/>
<input type="hidden" name="user" value="<?=$_SESSION['jaram_user_uid']?>"/>
<input type="hidden" name="vote_id" value="<?=$data['vote_id']?>"/>
<input type="hidden" name="page" value="<?=$_GET['page']?>"/>
<input type="hidden" name="article_num" value="<?=$_GET['article_num']?>"/>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="text">
<tr>
	<td><input type="text" name="comment" size="20" class="inputtext" style="width:100%;height:20px"/></td>
	<td width="70" align="right"><input type="image" src="/images/button/btn_post.gif" value="글쓰기"/></td>
</tr>
</table>
</form>

<div align="right">
<?
if ( $_SESSION[jaram_user_uid] == $data[user_uid] )
	echo("<a href=\"delete.php?action=delete&amp;vote_id=$_GET[vote_id]&amp;article_num=$_GET[article_num]\"><b>Delete</b></a>&nbsp;");
?>
<a href="modify.php?action=modify&amp;vote_id=<?=$_GET[vote_id]?>&amp;article_num=<?=$_GET[article_num]?>&amp;page=<?=$_GET[page]?>"><b>Modify</b></a>&nbsp;&nbsp;<a href="index.php?page=<?=$_GET[page]?>"><b>List</b></a></div>

<?
############## footer #################
include INCLUDE_PATH."/footer.inc.php";
?>