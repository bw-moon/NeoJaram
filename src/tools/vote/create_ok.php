<?php
/*************************************
 *			create_ok.php								*
 *			@decription : create new vote	*
 *			@author : serue						*
 *************************************/

	include "dbconn.php";					// database connect
	include "./lib/function.php";			// function include
	include "./lib/admin_conf.php";	// admin config include


	############## header ################
	include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");



	############## body ##################

	$topic_text = addslashes(htmlspecialchars($_POST['topic_text']));
	$vote_start = strtotime($_POST['vote_start']);
	$vote_limit = strtotime($_POST['vote_limit']);

	$topic_comment = addslashes(htmlspecialchars($_POST['topic_comment']));

	$temp = split("\*", str_replace("\n", "", $_POST['option_text']));

	for( $i = 0; $i < count($temp) - 1 ; $i++ ) {
		$temp[$i] = addslashes(htmlspecialchars(trim($temp[$i+1])));
	}
	$option_text = $temp;
?>
	<table border="0" align="center">
		<tr>
			<td align="center">
				<b>Create Vote</b><br/><br/>

				<form method="POST" action="./submit_ok.php?action=create" name="topic">
					
					<input type="hidden" value="<?=$topic_text?>" name="topic_text"/>
					<input type="hidden" value="<?=$vote_start?>" name="vote_start"/>
					<input type="hidden" value="<?=$vote_limit?>" name="vote_limit"/>
					<input type="hidden" value="<?=$topic_comment?>" name="topic_comment"/>
					<input type="hidden" value="<?=$_POST['is_open']?>" name="is_open"/>
					<?
					for( $i = 0 ; $i < count($option_text) ; $i++ ) {
					?>
					<input type="hidden" value="<?=$option_text[$i]?>" name="option_text[<?=$i?>]">	
					<?
					}
					?>
					<div align="center">

					<b>Topic : </b><?=$topic_text?><br/><br/>
					<b>vote start (yyyy-mm-dd)</b> : <?=$_POST['vote_start']?><br/>
					<b>vote limit (yyyy-mm-dd)</b> : <?=$_POST['vote_limit']?><br/><br/>

					<b>Comment</b><br/>
					<?=$topic_comment?><br/><br/>

					<b>공개여부 :</b> <? if ( $_POST['is_open'] == "on" )  { echo "공개<br/><br/>"; } else { echo "비공개<br/><br/>"; } ?>

					<b>Option</b>
<?
					for ( $i = 0 ; $i < count($option_text) - 1 ; $i++  ) { 
						echo "<br/>option $i : $option_text[$i]";
					}
?>			
				<br/><br/><input type="submit" value="Post vote">
				</form>
				

			</td>
		</tr>
	</table>
<?

	############## footer #################
	include INCLUDE_PATH."/footer.inc.php";

?>