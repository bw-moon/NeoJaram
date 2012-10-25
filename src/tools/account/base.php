<?

include('./db_conn.php');

?>

<table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0' align='center'>
<tr>
<td valign='top'>
	<!--처음 테두리 테이블 (가로길이는 300 + 10 + 300 입니다.)-->
	<table border='0' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<td valign='top'>
		<!--3등분 테이블-->
		<table border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='top'>
			<!--왼쪽 테이블-->
			<table border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='top'>
				<!--date.php-->
				<!--
				날짜를 검색하는 페이지 입니다.
				이곳에서 날짜에 대한 정보를 다른페이지로 넘겨줍니다.
				-->
				<? include ('./date.php'); ?>
			</td>
			</tr>
			<tr>
			<td valign='top'>
				<!--outgoing.php-->
				<!--
				이곳은 지출 내역을 출력하는 곳입니다.
				date.php 페이지에서 날짜를 전달 받아 그 값으로
				월 별 내역을 출력합니다.
				-->
				<? include ('./outgoing.php'); ?>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--공백-->
			<table width='10' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='top'>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--오른쪽 테이블-->
			<table border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td valign='top'>
				<!--account.php-->
				<!--
				이곳에 정산 자동 기능 소스가 포함되어 있습니다.
				그 달의 총잔액과 은행잔고 현금잔액을 표시 합니다.
				-->
				<?
				$select_auto=mysql_query("select auto from auto where no='1'", $dbconnect);
				$result_auto=mysql_fetch_row($select_auto);
				
				if($result_auto[0]=='수동')
					include ('./account.php');

				else if($result_auto[0]=='자동')
					include ('./account_auto.php');
				?>
			</td>
			</tr>
			<tr>
			<td valign='top'>
				<!--income.php-->
				<!--
				이곳은 수입 내역을 출력하는 곳입니다.
				date.php 페이지에서 날짜를 전달 받아 그 값으로
				월 별 내역을 출력합니다.
				-->
				<? include ('./income.php'); ?>
			</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--공백-->
		<table height='20' border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='top'>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td valign='top'>
		<!--회계 관리자만 볼수 있는 곳-->
		<table border='0' cellpadding='0' cellspacing='0' align='center'>
		<tr>
		<td valign='top'>
			<!--insert_form.php-->
			<!--
			회계 관리자가 수입 또는 지출 내역을 적는 곳 입니다.
			-->
			<? include ('./insert_form.php'); ?>
		</td>
		<td valign='top'>
			<table width='10' border='0' cellpadding='0' cellspacing='0' align='center'>
			<tr>
			<td>
			</td>
			</tr>
			</table>
		</td>
		<td valign='top'>
			<!--settle_form.php-->
			<!--
			월말 정산을 하면 이곳에서 출력합니다.
			첫번째 달은 스스로 정산버튼을 눌어줘야 합니다.
			두번째 달부터는 자동 기능을 사용할 수 있습니다.
			-->
			<?
				$select_auto=mysql_query("select auto from auto where no='1'", $dbconnect);
				$result_auto=mysql_fetch_row($select_auto);
				
				if($result_auto[0]=='수동')
					include ('./settle_form.php');

				else if($result_auto[0]=='자동')
					include ('./settle_form_auto.php');
			?>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	<? mysql_close($dbconnect); ?> <!--mysql 끝내기-->
	</table>
</td>
</tr>
</table>