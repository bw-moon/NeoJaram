<?php
/*
 * 일정관리에서 사용하는 함수와 변수들
 * by 18th 문병원
 */

function getSafeDate($data, $type) {
	if (empty($data)) {
		return date($type);
	} else {
		return $data;
	}
}


function get_monday_before ( $year, $month, $day ) { 
  $weekday = date (  "w", mktime ( 2, 0, 0, $month, $day, $year ) ); 
  if ( $weekday == 0 ) 
    return mktime ( 2, 0, 0, $month, $day - 6, $year ); 
  if ( $weekday == 1 ) 
    return mktime ( 2, 0, 0, $month, $day, $year ); 
  return mktime ( 2, 0, 0, $month, $day - ( $weekday - 1 ), $year ); 
} 



function get_sunday_before ( $year, $month, $day ) { 
  $weekday = date (  "w", mktime ( 2, 0, 0, $month, $day, $year ) ); 
  $newdate = mktime ( 2, 0, 0, $month, $day - $weekday, $year ); 
  return $newdate; 
} 


// 실행시간 계산
function elapsed($start)
{
  $end = microtime();
   list($start2, $start1) = explode(" ", $start);
   list($end2, $end1) = explode(" ", $end);
  $diff1 = $end1 - $start1;
   $diff2 = $end2 - $start2;
   if( $diff2 < 0 ){
       $diff1 -= 1;
       $diff2 += 1.0;
  }
   return $diff2 + $diff1;
}

function is_today($yy, $mm, $dd) {
	return $yy == date('Y') && $dd == date('d') && $mm == date("n");
}

function getScheduleStr($schedule_list, $schedule_data, $date) {
	if (isset($schedule_list[$date]))
	{
		$plans .= "<ul>";
		for ($j = 0; $j < $schedule_list[$date]; $j++)
		{
			$plans .= "<li>";
			$plans .= "<a href=\"".$_SERVER['REQUEST_URI']."#".$schedule_data[$date][$j]['schedule_id']."\">";
			$plans .= $schedule_data[$date][$j]['schedule_subject']."</a></li>";
		}
		$plans .= "</ul>";
	} else {
		$plans = "";
	}
	return $plans;
}
?>