<?
include_once(realpath('../../lib/includes')."/library.inc.php");


dbconn();

$sql = 'INSERT INTO `jaram_seminar` ( `seminar_id` , `seminar_topic` , `seminar_desc` , `seminar_topics` , `seminar_benefit` , `seminar_file` , `seminar_group_id` ) ';
$sql .= 'VALUES ( \'\', \'\', NULL , \'\', \'\', NULL , \'0\' );';
$sql .= ''; 

mysql_query($sql);
printf("Last inserted record has id %d\n", mysql_insert_id());


include (INCLUDE_PATH."/footer.inc.php");
?>