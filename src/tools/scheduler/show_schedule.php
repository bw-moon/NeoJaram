자람 정회원이면 누구나 일정을 추가할 수 있습니다. 
<p>
아래 달력에서 추가하고 싶은 날을 클릭하면 일정을 추가 할 수 있고 일정을 삭제하기 위해서는 하단의 일정목록에서 일정 제목을 클릭하시면 됩니다.
</p>
<?
// 서버 접속 후, 디비연결
dbconn();

// 년도가 지정되지 않았을 경우 현재 년도로 지정
$yy = getSafeDate($_GET['yy'], "Y");
// 월이 지정되지 않았을 경우 현재 월로 지정
$mm = getSafeDate($_GET['mm'], "m");
// 일이 지정되지 않았을 경우 현재 일로 지정
$dd = getSafeDate($_GET['dd'], "d");

// 잘못된 입력 검출
if (!checkdate($mm, $dd, $yy))  {
	show_error_msg("잘못된 변수 입력입니다.\n오늘 날자를 기준으로 표시합니다.", "std", "", "");
	$mm = date("m");
	$yy = date("Y");
}
$lastday=array(31,date("t", mktime(0,0,0,$_GET['mm'], 1, $_GET['yy'])) ,31,30,31,30,31,31,30,31,30,31);// 각 달의 마지막 날 지정 
$dayname=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");// 요일명 지정 

echo "<h3>".$yy." / ".$mm."</h3>";


// 데이터 가져오기
$schedule_period = $yy.$mm;

$query = "SELECT u.user_name,u.uid,s.schedule_id,s.schedule_subject,s.schedule_text,s.schedule_start,s.schedule_period,s.seminar_id FROM jaram_schedule AS s LEFT JOIN jaram_users AS u ON s.uid = u.uid WHERE schedule_period='$schedule_period' ORDER BY schedule_start ASC;";

$result = mysql_query($query);

/*
 * 보여지는 연도, 월에 맞는 데이터를 한번에 가져와서 3차 배열의 형식으로
 * 년,월,일의 숫자를 붙여쓴 8자리의 수를 일차 배열의 key로 가진다.
 * 0부터 시작하는 정수값을 2차 key로 가진다.
 */

$EACH_DAY_PLAN = array(); //날짜별 일정의 수 

while ($ary = mysql_fetch_assoc($result))
{ 
	if ($ary['schedule_start'] != $post_start) {
		$plan_count = 0;
	}

	while (list($key,$val) = each($ary))
	{
		$val = HTMLSpecialChars($val, ENT_QUOTES);
		$data[$ary['schedule_start']][$plan_count][$key] = $val;
	}
	$plan_count++;
	$EACH_DAY_PLAN[$ary['schedule_start']]++;
	$post_start = $ary['schedule_start'];
}

// 시작요일 
$sday = date("w", mktime(0,0,0, (int)$mm, 1, $yy));

echo("
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" id=\"fancy_table\">\n
<thead>
<tr>\n");// 년 - 월 출력 

for($i=0;$i<7;$i++){ // 요일명 출력 
	if($i==0) echo("<th width=\"13.3%\" class=\"title_left\"><span class=\"holiday\">$dayname[$i]</span></th>\n"); 
	else if($i==6) echo("<th width=\"13.3%\"  class=\"title_right\"><span class=\"holiday2\">$dayname[$i]</th>\n"); 
	else echo("<th width=\"13.3%\" >$dayname[$i]</th>\n"); 
}

echo("</tr>
</thead>
<tbody><tr>"); 

// 처음부터 시작 요일 값까진 전달 보여주기 
for($i=1;$i<=$sday;$i++)  {
	//$logger->debug($sday);
	$prev_day = $lastday[(12+$mm-2)%12]-$sday+$i;
	echo("<td class=\"other_month\">{$prev_day}</td>\n");
}

$c=$sday;// 임시변수는 시작 요일값으로 지정 

for($i=1;$i<=$lastday[$mm-1];$i++) { // 1부터 해당 월의 마지막 날까지 반복 
	$c++;// 임시 변수 증가
	$daySch="";$j=0; 

	$chk=$c%7; 
	
	$day = sprintf("%02d", $i);
	$reg_date = $yy.$mm.$day;
	
	$plans = getScheduleStr($EACH_DAY_PLAN, $data, $reg_date);
	
	$today_flag = "";
	
	if (is_today($yy, $mm, $day)) {
		$style = "today";
		$today_flag = "<span class=\"holiday\">today</span>";
	} else if (!empty($plans)) {
		$style = "scheduled";
	} else if ($chk == 1) {
		$style = "holiday";
	} else if ($chk == 0) {
		$style = "holiday2";
	} else {
		$style = "blank";
	}

	echo("<td class=\"{$style}\"><a href=\"./posting.php?mode=newplan&amp;date={$reg_date}\" class=\"{$style}\">{$day}</a> {$today_flag}\n {$plans}</td>\n");

	$plans = "";

	if(!$chk&&$i!=$lastday[$mm-1]){ 
		echo("</tr>\n<tr>");// 7로 나눠 떨어지면 다음줄로 (토요일마다 다음줄) 
		$daySum=0; 
	} else $daySum++; 
} 

for($i=1;$i<8-$daySum;$i++) {
	$next_day = $i;
	echo("<td class=\"other_month\">{$next_day}</td>\n");
}
	echo("</tr></tbody></table>");


# 다른달 보여주기
// tempvalue copy
$yn = $yy;
$yv = $yy;

// temp value

$nextmonth = $mm + 1;

if ($mm == 1) {
	$yv = $yy - 1;
	$mm = 13;
}

$prevmonth = $mm - 1;

if ($nextmonth == 13)
{
	$nextmonth = 11;

	if($mm == 12)
		$nextmonth = 1;

	$yn = $yy + 1;
}

$prev_link = "./?yy=".$yv."&mm=".trim(sprintf("%02d ",$prevmonth));
$next_link = "./?yy=".$yn."&mm=".trim(sprintf("%02d ",$nextmonth));
$pre_month = "<a href=\"{$prev_link}\">prev</a>&nbsp;";
$now_month = "<a href=\"./?yy=".date("Y")."&amp;mm=".date("m")."\">today</a>";
$next_month = "&nbsp;<a href=\"{$next_link}\">next</a>";
echo "<script type=\"text/javascript\">
<!--
previous_link = '{$prev_link}';
next_link = '{$next_link}';
-->
</script>";

echo "<center>$pre_month $now_month $next_month</center><br />";

if (count($EACH_DAY_PLAN) > 0)
{
	echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#FFFFFF\" border=\"0\">";
	for($i = 1; $i <= $lastday[$mm-1]; $i++)
	{
		$day = sprintf("%02d", $i);
		$reg_date = $yy.$mm.$day;

		
		if (isset($EACH_DAY_PLAN[$reg_date]))
		{
			for ($j = 0; $j < $EACH_DAY_PLAN[$reg_date]; $j++)
			{
				if ($data[$reg_date][$j]['seminar_id'] != 0)
					$seminar = "&nbsp;<span class=\"hot small\">seminar!</span>";
				else
					$seminar = "";

				if ($day == $dd && date("m") == $mm && date("Y") == $yy)
					$seminar = " <span class=\"hot small\">today</span>"." ".$seminar;

				if (!empty($data[$reg_date][$j]['user_name']))
					$written_by = "<span class=\"small\">author : </span> <a href=\"/jaram/memberinfo/?gid=".$data[$reg_date][$j]['uid']."\">".$data[$reg_date][$j]['user_name']."</a>";

				if (!empty($_SESSION['jaram_user_name']))
                    $plan_subject = "<a href=\"./posting.php?mode=delplan&amp;id=".$data[$reg_date][$j]['schedule_id']."\">".$data[$reg_date][$j]['schedule_subject']."</a>";
				else
					$plan_subject = $data[$reg_date][$j]['schedule_subject'];

				echo "<tr><td width=\"150\" bgcolor=\"#F2F2F2\" valign=\"top\">";
				echo "<img src=\"../../images/bullet1.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"".$data[$reg_date][$j]['schedule_id']."\" />&nbsp;<b><a name=\"".$data[$reg_date][$j]['schedule_id']."\">".$day."일</a></b>".$seminar."<br />".$plan_subject."</td><td valign=\"top\">";
				echo nl2br(HTMLSpecialChars(auto_link($data[$reg_date][$j]['schedule_text'])))."<br/><div align=\"right\">".$written_by."</div>";
				echo "</td></tr>\n";
			}			
		}
	}
	echo "</table>";

}

?>