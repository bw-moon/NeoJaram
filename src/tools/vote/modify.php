<?php

/*************************************
 *			modify.php								*
 *			@decription : modify vote			*
 *			@author : serue						*
 *************************************/

	############## header ################
	include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");


	include "./lib/function.php";			// function include
	include "./lib/admin_conf.php";	// admin config include

	// 데이터베이스 접속
	dbconn();

	############## body ##################

	// 수정할 자료를 불러온다
	$table = 'jaram_vote';
	$query_modify = "SELECT * FROM $table WHERE vote_id=$vote_id ORDER BY vote_id";
	
	$result = mysql_query($query_modify);
	$data = mysql_fetch_array($result);
	
	if ( $data[user_uid] == $_SESSION["jaram_user_uid"] ) {
	?>
	<form method="POST" action="submit_ok?action=modify&amp;vote_id=<?=$data[vote_id]?>&amp;page=<?=$_GET["page"]?>&amp;article_num=<?=$_GET["article_num"]?>">

	<table class="text" align="center">
		<tr valign="top">
			<td align="center">Topic</td>
			<td><input type="text" name="topic_text" value="<?=$data[topic_text]?>" size="45" class="text"/><br/><br/></td>
		</tr>
		<tr>
			<td align="center">vote start (yyyymmdd)</td>
			<td><input type="text" name="vote_start" value="<?=date("Y-m-d", $data[vote_start])?>" class="text"/></td>
		</tr>
		<tr valign="top">
			<td align="center">vote limit (yyyymmdd)</td>
			<td><input type="text" name="vote_limit" value="<?=date("Y-m-d", $data[vote_limit])?>" class="text"/><br/><br/></td>
		</tr>
		<tr>
			<td align="center">comment</td>
			<td><textarea name="topic_comment" cols="50" rows="10" class="text"><?=stripslashes($data[topic_comment])?></textarea></td>
		</tr>
		<tr valign="top">
			<td align="center">공개여부</td>
			<td><input type="checkbox" name="is_open" <?=($data["is_open"] == "on")? "checked" : ""?>/><br/><br/></td>
		</tr>
<?
		$table = 'jaram_vote_option';
		$query_option = "SELECT * FROM $table WHERE vote_id=$vote_id ORDER BY vote_option_id";
		$result_option = mysql_query($query_option);

		$i = 1;

		while ( $option = mysql_fetch_array($result_option) ) {
			// 이미 투표 된 경우에는 수정이 불가~
			if ($option["option_result"] > 0) {
				echo "\t\t<tr>\n\t\t\t<td>option $i</td>\n\t\t\t<td>$option[option_text]\n<input type=\"hidden\" name=\"option_text$i\" value=\"$option[option_text]\"/>\n<input type=\"hidden\" name=\"option_id$i\" value=\"$option[vote_option_id]\"/>\n</td>\n\t\t</tr>\n";
			} else {
				echo "\t\t<tr>\n\t\t\t<td>option $i</td>\n\t\t\t<td><input type=\"text\" name=\"option_text$i\" value=\"$option[option_text]\" size=\"50\" class=\"text\"/>\n<input type=\"hidden\" name=\"option_id$i\" value=\"$option[vote_option_id]\"/>\n</td>\n\t\t</tr>\n";
			}
			$i++;
		} // end of while

?>
		<tr>
			<td align="right" colspan="2">
				<input type="hidden" name="vote_option_count" value="<?=($i - 1)?>"/>
				<input type="submit" value="modify" class="text"/>
			</td>
		</tr>
	</table>
	</form>
<?
	} else {
		show_error_msg("이 글을 고칠 권한이 없습니다", "back");
		exit;
	} // end of if

	############## footer #################
	include INCLUDE_PATH."/footer.inc.php";
?>