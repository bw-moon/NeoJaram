<?
function show_short_plan($PLAN, $state)
{
	global $show_priod;

	if ($state == $show_priod)
	{
		$now_style = "<span style=\"background-color: #def;\">";
		$now_style_end = "</span>";
	} else {
		$now_style = "";
		$now_style_end = "";
	}

	if ($PLAN['schedule_dday'] > 0)
	{
		$dday = get_dday($PLAN['schedule_start']);

		if ($dday < 0) {
			$dday = "D".$dday;
			$show_dday = " (<b><font color=\"red\">".$dday."</font></b>)";
		} elseif ($dday == 0) {
			$show_dday = " (<b><font color=\"red\">D-Day!</font></b>)";
		} else {
			$show_dday = "";
		}
	}

	if (!empty($PLAN['seminar_id']))
	{
		$PLAN['schedule_subject'] = "<a href=\"".WEB_ABS_PATH."/studyzone/seminar/seminar_view.php?seminar_id=".$PLAN['seminar_id']."\">".$PLAN['schedule_subject']."</a>";

	}

	$rv = get_each_date($state);
	return "<span class=\"ver7\" style=\"color:#777777\">[".$rv['month']."/".$rv['day']."]</span> ".$now_style.$PLAN['schedule_subject'].$now_style_end.$show_dday."<br/>";
}

$lastday=array(31,28,31,30,31,30,31,31,30,31,30,31);// 각 달의 마지막 날 지정 
$dayname=array("일","월","화","수","목","금","토");// 요일명 지정 

$yy = date("Y");// 년도가 지정되지 않았을 경우 현재 년도로 지정 
$mm = date("m");// 월이 지정되지 않았을 경우 현재 월로 지정 
$dd = date("d");

dbconn();

// 데이터 가져오기
$schedule_period = $yy.$mm;
$query = "SELECT schedule_subject,schedule_start,schedule_dday,schedule_id,seminar_id FROM jaram_schedule WHERE schedule_period='$schedule_period' ORDER BY schedule_start ASC;";

//echo $query;

$result = mysql_query($query) or die(mysql_error());

$MINI_EACH_DAY_PLAN = array(); //날짜별 일정의 수 

while ($ary = mysql_fetch_array($result))
{ 
	if ($ary['schedule_start'] != $post_start)
		$plan_count = 0;

	while (list($key,$val) = each($ary))
	{
		$val = HTMLSpecialChars($val, ENT_QUOTES);
		$MINI_DATA[$ary['schedule_start']][$plan_count][$key] = $val;
	}
	$plan_count++;
	$MINI_EACH_DAY_PLAN[$ary['schedule_start']]++;
	$post_start = $ary['schedule_start'];
}

//print_r($MINI_DATA);

// 윤년 계산을 통해 2월의 마지막 날 계산 
if($yy%4==0 && $yy%100!=0 || $yy%400==0) $lastday[1]=29; 

// 전해까지 평년 기준으로 날짜수 계산 및 윤년의 횟수를 더함 
$total=($yy-1)*365+(int)(($yy-1)/4) - (int)(($yy-1)/100) + (int)(($yy-1)/400); 

for($i=0;$i<$mm-1;$i++) $total+=$lastday[$i];// 전달까지의 날짜수 더함 
$total++;// 그 달의 1일 
$sday = $total%7;// 시작 요일을 구함 (0-일요일,...,6-토요일) 

echo("<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\" class=\"thm8\"><tr>");// 년 - 월 출력 

for($i=0;$i<7;$i++){ // 요일명 출력 
	if($i==0) echo("<td align=\"center\"><font color=\"red\"><b>$dayname[$i]</b></font></td>\n"); 
	else if($i==6) echo("<td align=\"center\"><font color=\"blue\"><b>$dayname[$i]</b></font></td>\n"); 
	else echo("<td align=\"center\"><b>$dayname[$i]</b></td>\n"); 
}

echo("</tr>\n<tr>"); 

// 처음부터 시작 요일값까진 공백처리 
for($i=1;$i<=$sday;$i++) echo("<td height=\"10\">&nbsp;</td>\n"); 
$c = $sday;// 임시변수는 시작 요일값으로 지정 

for($i=1;$i<=$lastday[$mm-1];$i++) { // 1부터 해당 월의 마지막 날까지 반복 
	$c++;// 임시 변수 증가 
	$daySch="";$j=0; 

	$chk=$c%7;
	
	$today = "";
	$day = trim(sprintf("%02d ",$i));

	if ($i == $dd)
	{
		$today = " bgcolor=\"#FFCC33\"";
	}

	$schedule_start = (int)date("Ym",time()).$day;

	if (is_array($MINI_DATA[$schedule_start]))
	{
		$day = "<a href=\"".WEB_ABS_PATH."/tools/scheduler/#".$MINI_DATA[$schedule_start][0]['schedule_id']."\"><b>".$day."</b></a>";
	}

	//echo $schedule_start." ".$MINI_DATA[$schedule_start][0]['schedule_subject']."<br/>";

	if($chk==1) echo("<td align=\"center\" valign=\"top\" height=\"10\"".$today."><font color=\"red\">".$day."</font></td>\n"); 
	else if(!$chk) echo("<td align=\"center\" valign=\"top\" height=\"10\"".$today."><font color=\"blue\">".$day."</font></td>\n");
	else echo("<td align=\"center\" valign=\"top\" height=\"10\"".$today.">".$day."</td>\n"); 

	if(!$chk&&$i!=$lastday[$mm-1]){ 
		echo("</tr>\n<tr>");// 7로 나눠 떨어지면 다음줄로 (토요일마다 다음줄) 
	}
} 

echo("</tr></table>"); 

// 오늘부터 10일후의 일정을 출력
$show_priod = (int)$yy.$mm.$dd;
//$show_priod = 20030512;
for ($i = $show_priod; $i <= $show_priod + 10; $i++)
{
	if (is_array($MINI_DATA[$i]))
	{
		for ($j = 0; $j < $MINI_EACH_DAY_PLAN[$i]; $j++)
		{
			echo show_short_plan($MINI_DATA[$i][$j], $i);
		}
	}
}
?>