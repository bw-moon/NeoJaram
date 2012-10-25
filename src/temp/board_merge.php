<?php
include_once(realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");
echo "Let's Start!!<br/><br/>\n\n";
flush();
set_time_limit(0);

function microtime_float()
{
    $time = microtime();
    return (double)substr( $time, 11 ) + (double)substr( $time, 0, 8 );
}

$dbo = ZendDB::getDBO();

$skip_flag = false;
if ($_GET['table_point']) {$skip_flag = true;}

if (!$skip_flag) {
    echo "Truncate target table<br/><br/>\n";
    $dbo->query("TRUNCATE TABLE jaram_board");
    $dbo->query("TRUNCATE TABLE jaram_board_comment");
    $dbo->query("TRUNCATE TABLE jaram_board_file");
}

$tables = $dbo->fetchCol("SHOW TABLES");
$table_list = $dbo->fetchAll("SELECT name FROM mboard_admin");
$interval = 100;

$running_time = 0;
$average_time = 0;

foreach ($table_list as $table) {
    if ($skip_flag && $_GET['table_point'] != $table['name']) continue;


    $table_data = "mboard_".$table['name'];
    $table_comment = "mboard_{$table['name']}_re";
    $table_file = "mboard_{$table['name']}_file";

    $row_count = $dbo->fetchOne("SELECT COUNT(*) FROM {$table_data}");

    if ($average_time > 0) {
        $temp = $average_time * ($row_count/10);
        if ($temp < 60) {
            $temp .= " sec.";
        } else if ($temp < 60*60) {
            $temp = ($temp/60)." min.";
        } else {
            $temp = ($temp/(60*60)). " hour";
        }
        $guess = "예상 변환 시간 : {$temp}";
    }
    echo "변환 : {$table['name']} (total : {$row_count} article),  변환예상시간 : {$temp}<br/>\n";
    flush();
    

    if (in_array($table_data, $tables)) {
        $start = microtime_float();
        $count = 0;

        for ($i = 0; $i < $row_count; $i += $interval) {

            $data = $dbo->fetchAssoc("SELECT * FROM {$table_data} LIMIT {$i}, {$interval}");
            foreach ($data as $row) {

                if ($_GET['table_point'] == $table['name'] && $_GET['count_point'] == $count) {
                    $to_delete = $dbo->fetchAll("SELECT id FROM jaram_board WHERE bid=:bid AND old_id>=:id", array('bid'=>$table['name'], 'id'=>$row['id']));
                    foreach ($to_delete as $delete) {
                        $dbo->query("DELETE FROM jaram_board_comment WHERE subid=:id", array('id'=>$delete['id']));
                        $dbo->query("DELETE FROM jaram_board_file WHERE sub_id=:id", array('id'=>$delete['id']));
                    }
                    echo " delete overflow thins : ".count($to_delete)." item ";
                    $skip_flag = false;
                }

                if ($skip_flag) {
                    $count++;
                    continue;
                }


                $row['old_id'] = $row['id'];
                unset($row['id']);
                $row['bid']=$table['name'];
            
                $dbo->insert('jaram_board', $row);
                $new_id = $dbo->lastInsertId();

                if (in_array($table_comment, $tables)) {
                    // insert comments
                    $comments = $dbo->fetchAssoc("SELECT * FROM {$table_comment} WHERE subid=:id", array('id'=>$row['old_id']));
                    foreach ($comments as $comment) {
                        unset($comment['id']);
                        $comment['subid'] = $new_id;
                        $dbo->insert('jaram_board_comment', $comment);
                    }
                    unset($comments);
                }

                if (in_array($table_file, $tables)) {
                    // insert files
                    $files = $dbo->fetchAssoc("SELECT * FROM {$table_file} WHERE sub_id=:id", array('id'=>$row['old_id']));

                    foreach ($files as $file) {
                        unset($file['file_id']);
                        $file['sub_id'] = $new_id;
                     
                        $dbo->insert('jaram_board_file', $file);
                    }
                    unset($files);
                }

                $count++;
                if ($count % $interval) {
                    echo ".";
                } else {
                    echo " <a href=\"./board_merge.php?table_point={$table['name']}&count_point={$count}\">{$count}</a> ";
                }
                flush();
            }
            unset($data);
            flush();
        } 
        $running_time = microtime_float() - $start;
        $average_time = $running_time / ($row_count/10);
        echo "<br/>변환에 걸린 시간 : {$running_time} sec. ";
        echo "<br/><br/>\n";
    }
}