<?php
/*************************************
 *	view_list.php			    		*
 *	@decription : view all list			*
 *	@author : serue						*
 *************************************/

############## header ################
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");

include "./lib/function.php";			// function include
include "./lib/admin_conf.php";	// admin config include


// 데이터베이스 접속
dbconn();


############## main body ################

	$user_uid = $_SESSION['jaram_user_uid'];

	$table="jaram_vote";		// table명
	
	// vote 테이블에서 현재 진행중인 투표의 갯수를 알아낸다
	$qry_present = "SELECT * FROM $table where $date<=vote_limit ORDER BY vote_id";
	$result_present = mysql_query($qry_present);

	// 현재 진행중인 총 투표의 수
	$total_present = mysql_num_rows($result_present);
	
	// vote 테이블에서 모든 자료들을 불러온다
	$query = "SELECT * FROM $table ORDER BY vote_id DESC";
	$result = mysql_query($query);

	// 총 자료의 갯수
	$total = mysql_num_rows($result);

	
	//=============================================================== 
	## 	페이지 관련 정보(게시물 출력시 필요한)
	//===============================================================
	if(!$page){
			$page = 1;
	}

	// 한페이지에 출력할 게시물의 수
	$num_per_page = 10;

	// 출력할 게시물의 범위
	if(!$total) {
			$first = 1;
			$last = 0;
	} 
	else {
			$first = $num_per_page * ($page - 1);
			$last = $num_per_page * $page;
			
			$IsNext = $total - $last;
			
			if($IsNext > 0){
				$last -=  1;
			} else {
				$last  =  $total - 1;
			}
	}
	
	// 한페이지에 나올 페이지의 수
	$page_count = 10;

	// 전체 페이지수
	$total_page = ceil($total / $num_per_page);

	//==============================================================
	##		Interface 부분
	//==============================================================
?>
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr style="background-color: #555555">
	<td align="center" width="25" class="bbshead" height="25">No</td>
	<td align="center" class="bbshead">Topic</td>
	<td align="center" width="30" class="bbshead">Voters</td>
	<td align="center" width="40" class="bbshead">Open</td>
	<td align="center" width="30" class="bbshead">State</td>
</tr>
<tr>
	<td colspan="5" height="2" bgcolor="#999999"></td>
</tr>
<tr>
	<td colspan="5" height="1" bgcolor="#eeeeee"></td>
</tr>
<?
	// 각 글을 처음부터 차례대로 정렬
	for ( $i=$first , $article_num = $total-$num_per_page*($page-1) ; $i < ($page)*$num_per_page && $article_num>0 ; $i++, $article_num-- ){
		
		mysql_data_seek($result,$i);
		$data = mysql_fetch_array($result);

		/***** 투표별 참여자 수 *****/
		$result_qry = "select * from jaram_vote_option where vote_id=$data[vote_id] order by vote_id desc";
		$result_result = mysql_query($result_qry);

		$result_sum = 0;		// 참여자수 결과 초기화
		while ( $res=mysql_fetch_array( $result_result) ) {
			$result_sum = $result_sum + $res[option_result];		// 참여자수 합산
		}

		if ($data['vote_start'] < time() && $data['vote_limit'] < time()) {
			$VOTE_CLOSE = 1;
		} else {
			$VOTE_CLOSE = 0;
		}

		// list form
		echo("
		<tr>
			<td align=\"center\">$article_num</td>
			<td><a href=\"./survey_view.php?action=view&amp;page=$page&amp;vote_id=$data[vote_id]&amp;article_num=$article_num\">$data[topic_text]</a></td>
		");
		
		$data['is_open'] = ($data['is_open'] == "on") ? "공개" : "비공개";
		$doing = ($VOTE_CLOSE == 1) ? "<font color=\"999999\">종료</font>" : "진행";
		
		echo("
			<td align=\"center\">$result_sum</td>
			<td align=\"center\">$data[is_open]</td>
			<td align=\"center\">$doing</td>
		</tr>
		<tr>
			<td height=\"1\" colspan=\"5\" bgcolor=\"#CCCCCC\"></td>
		</tr>	
		");

		
		if ( ($first - $num_per_page) != $article_num && $article_num != 1) {
			echo("
			<tr>
				<td colspan=\"5\">
				</td>
			</tr>		
			");
		}

	}	
	

if ( $_SESSION['jaram_user_uid'] ) {?>
	<tr align="right">
		<td colspan="5" height="20">
			<a href="create.php?action=create"><b>Post</b><br/></a>
		</td>
	</tr><?
} else {?>
	<tr align="right">
		<td colspan="5" height="20">&nbsp;<br/>
		</td>
	</tr><?
}
?>

</table>

<div align="center">
<?
echo page_list($total_page, $num_per_page, $page, $HTTP_SERVER_VARS[PHP_SELF], "");
?>
</div>
<?
############## footer #################
include INCLUDE_PATH."/footer.inc.php";
?>
