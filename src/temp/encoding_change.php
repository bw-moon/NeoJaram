<?php
include_once(realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php");
echo "Let's Start!!<br/><br/>\n\n";
flush();

set_time_limit(0);

// 일단 만개 이상, 5만개가 넘지 않는 것들만 변경
$trans_min = 0;
$trans_limit = 50000;

// 한번에 처리할 글의 개수
$select_limit = 100;
$success_list = array();
/*
$success_list = array('jaram_auth_access',
	'jaram_bbs_monitor',
	'jaram_bookmark',
	'jaram_custom_menu',
	'jaram_freshman',
	'jaram_group_join_wait',
	'jaram_group_pool',
	'jaram_groups',
	'jaram_history',
	'jaram_library_book_instance',
	'jaram_library_book_shelf',
	'jaram_library_book_status',
	'jaram_library_book_url',
	'jaram_library_book_wiki',
	'jaram_library_books',
	'jaram_programs',
	'jaram_schedule',
	'jaram_seminar',
	'jaram_seminar_comment',
	'jaram_seminar_file',
	'jaram_seminar_reader',
	'jaram_spam_list',
	'jaram_user_group',
	'jaram_users',
	'jaram_vote',
	'jaram_vote_comment',
	'jaram_vote_option',
	'jaram_vote_result'
);
*/

$charset_sql_table = "ALTER TABLE `%s` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$charset_sql_db = "ALTER DATABASE `%s` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$charset_sql_column = "ALTER TABLE `%s` CHANGE `%s` `%s` %s CHARACTER SET utf8 COLLATE utf8_general_ci";
$count_sql = "SELECT COUNT(*) FROM %s";
$desc_sql = "DESC %s";
$select_sql = "SELECT * FROM %s LIMIT %d OFFSET %d";
$dbo = ZendDB::getDBO();
$success = array();
$fail = array();
$logger->info("DB ENCODING CHAGNE : EUC-KR -> UTF-8");

$table_list = $dbo->fetchAll("SHOW TABLES");


if ($_REQUEST['step'] != "prepare") {
    $logger->info(sprintf($charset_sql_db, $CONFIG->database->dbname));
    $dbo->query(sprintf($charset_sql_db, $CONFIG->database->dbname));

    foreach ($table_list as $table) {
        $table_name = array_pop($table);
        $logger->info($table_name." : ".sprintf($charset_sql_table, $table_name));
        $logger->flush();
        $dbo->query(sprintf($charset_sql_table, $table_name));
    }
}

$dbo->beginTransaction();
try {
foreach ($table_list as $table) {
	$table_name = array_pop($table);

	$success[$table_name] = 0;
	$fail[$table_name] = 0;

	printf("테이블 : %s, 인코딩 변경 시작</br>\n", $table_name);
	$data_count = $dbo->fetchOne(sprintf($count_sql, $table_name));
	printf("데이터 개수 : %d<br/>\n", $data_count);
	
	if ($data_count < $trans_min || $data_count > $trans_limit || in_array($table_name, $success_list)) {
		echo "Pass<br/><br/>";
		continue;
	}

	$to_change_array = array();
	$where_fields = array();

	$desc = $dbo->describeTable($table_name);
    // 키가 될 걸 찾아서 정리하고 컬럼의 인코딩도 변경
    foreach ($desc as $table_field) {
		if ($table_field['PRIMARY']) {
			$where_fields[] = $table_field['COLUMN_NAME'];
		} else if (strpos($table_field['DATA_TYPE'], "char") > -1 || strpos($table_field['DATA_TYPE'], "text") > -1) {
			$to_change_array[] = $table_field['COLUMN_NAME'];
            if (strpos($table_field['DATA_TYPE'], "char") > -1) {
                $type = $table_field['DATA_TYPE']."(".$table_field['LENGTH'].")";
            } else {
                $type = $table_field['DATA_TYPE'];
            }
            $sql = sprintf($charset_sql_column, $table_field['TABLE_NAME'], $table_field['COLUMN_NAME'], $table_field['COLUMN_NAME'], $type);
            $logger->info($sql);
            $dbo->query($sql);
		}
	}

    if ($REQUEST['step']=="prepare") continue;

	echo "변환중 : ";
	for ($i = 1; $i < $data_count; $i += $select_limit) {
		$update_array = array();
		$source_data = $dbo->fetchAll(sprintf($select_sql, $table_name, $select_limit, $i));
		foreach ($source_data as $data) {
			$update_array = array();
			foreach ($to_change_array as $field) {
                $converted_data = iconv("EUC-KR", "UTF-8", $data[$field]);
                $update_array[$field] = $converted_data;
			}
			$where = "";
			$where_array = array();
			foreach ($where_fields as $field) {
				$where_array[] = $dbo->quoteInto("{$field}=?", $data[$field]);
			}
			$where = implode(" AND ", $where_array);
			if ($where && $update_array) {
                $result = $dbo->update($table_name, $update_array, $where);
                if ($result) {
					$success[$table_name]++;
				} else {
                    if ($fail[$table_name] % 1000 == 0) {
                        $logger->info($fail[$table_name].":".$table_name." ".$where);
                        $logger->debug($update_array);
                    }
					$fail[$table_name]++;
				}
			}
		}
        if ($i % 2000 == 0) {
            echo " {$i} ";
        } else {
		    echo " . ";
        }
		flush();
	}
	echo "<br/>변환 : {$success[$table_name]}개, 유지 : {$fail[$table_name]}개<br/>\n";
	echo "<br/><br/>\n";
	flush();
}
$dbo->commit();
} catch (Exception $e) {
    $dbo->rollBack();
	$logger->err($e);
	$logger->err($where);
    echo $e->getMessage();
}
?>