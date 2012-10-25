<?php
if (!isset($_GET['year'])) {
	$year = date("Y");
} else {
	$year = $_GET['year'];
}

$dbo = ZendDB::getDBO();
$min = $dbo->fetchOne("SELECT MIN(b.schedule_start) FROM jaram_seminar AS a LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id ");
$max = date("Y");

$links = array();
for ($i = substr($min, 0, 4); $i <= $max; $i++) 
{
	if ($i == $year) {
		$links[] = "<a href=\"./?year=".$i."\" class=\"impact\">".$i."</a>";
	} else {
		$links[] = "<a href=\"./?year=".$i."\">".$i."</a>";
	}
}
$link_tag = implode(" · ", $links);


$seminar_data = array();
for ($i = 1; $i <= 12; $i++) {
	$date = $year.sprintf("%02d",$i);

    $query = "SELECT a.*, c.group_name, a.seminar_file, b.schedule_start, (SELECT COUNT(*) FROM jaram_seminar_comment WHERE seminar_id=a.seminar_id) AS comment_count
		  FROM jaram_seminar AS a LEFT JOIN jaram_schedule AS b ON a.seminar_id = b.seminar_id 
		  LEFT JOIN jaram_groups AS c ON a.seminar_group_id = c.gid
		  WHERE b.schedule_start LIKE '$date%'
		  ORDER BY b.schedule_start ASC;";

    $rows = $dbo->fetchAll($query);

	// 마이그레이션을 위한 코드, seminar_group_id 값을 참조하여 seminar_group_name에 입력
	foreach ($rows as $row) {
		if (!$row['seminar_group_name']) {
			$dbo->update('jaram_seminar', array('seminar_group_name'=>$row['group_name']), "seminar_id={$row['seminar_id']}");
		}
	}

	if ($rows) { 
        $seminar_data["{$year}년 {$i}월"] = $rows;
	} 
}

$smarty = new JaramSmarty();
$smarty->assign('seminars', $seminar_data);
$smarty->assign('links', $link_tag);
$smarty->display('studyzone/seminar_list.tpl');

?>