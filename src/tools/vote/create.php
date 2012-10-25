<?php
/*************************************
 *			create.php								*
 *			@decription : create new vote	*
 *			@author : serue						*
 *************************************/

	############## header ################
	include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");


	include "./lib/function.php";			// function include
	include "./lib/admin_conf.php";	// admin config include

	// 데이터베이스 접속
	dbconn();

	############## body ##################

?>
	<table border="0" align="center">
		<tr>
			<td align="center">
				<b>Create Vote</b><br/><br/>

				<form method="POST" action="./create_ok.php">

					<div align="center">

					<b>Topic</b>
					<input type="text" name="topic_text" size="50"/><br/><br/>
					<b>vote start (yyyy-mm-dd)</b><input type="text" name="vote_start" size="10" value="<?=date("Y-m-d")?>"/><br/>
					<b>vote limit (yyyy-mm-dd)</b><input type="text" name="vote_limit" size="10" value="<?=date("Y-m-")?>"/><br/><br/>

					<b>Comment</b><br/>
					<textarea name="topic_comment" cols="80" rows="10"><?=$topic_comment?></textarea><br/><br/>
					
					공개여부 <input type="checkbox" name="is_open"/><br/><br/>

					</div>

					<div align="center">

					<b>Option</b>
					<p>example><br/>* option1<br/>* option2<br/>* option3<br/>.....<br/>
					</p>
					<textarea name="option_text" cols="80" rows="10"></textarea><input type="submit" name="create_option"/>
					
					</div>

				</form>

			</td>
		</tr>
	</table>
<?

	############## footer #################
	include INCLUDE_PATH."/footer.inc.php";

?>