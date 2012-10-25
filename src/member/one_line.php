<?
// TODO: 이 루틴은 위험할 수 있는 방법이다. 차후 안전을 위한 작업을 취해야 함.
$call_func_name = strtolower(sprintf("%s_%s", $_REQUEST['page'], $_REQUEST['action']));

if (function_exists($call_func_name)) {
	if (call_user_func($call_func_name, $_REQUEST) < 0) {
		echo "작업이 실패했습니다. - ".$call_func_name;
	}
} else {
	// 비정상적인 접근
}

function one_line_delete($data) {
	$dbo = ZendDB::getDBO();

	$where = $dbo->quoteInto('msg_id=?', $data['msg_id']);
	$where .= " AND ". $dbo->quoteInto('uid=?', $_SESSION['jaram_user_uid']);
	$result = $dbo->delete('jaram_one_line', $where);

	// 정상적으로 메시지가 삭제된 경우 관련 정보 삭제
	if ($result) {
		$where = $dbo->quoteInto('msg_id=?', $data['msg_id']);
		$dbo->delete('jaram_one_line_user', $where);
		echo "삭제되었습니다.";
	} else {
		$logger = getLogger();
		$logger->debug($result);
		echo "삭제를 실패했습니다.";
	}
	p_redirect("./?page=one_line&action=list");
}




function one_line_post($data) {
	$dbo = ZendDB::getDBO();

	$start_date = sprintf("%04d-%02d-%02d", $data['Date_Year'], $data['Date_Month'], $data['Date_Day']);
	
	$data['period'] = abs($data['period']-1);
    
	if (!is_int($data['period'])) $data['period'] = 1;
	
	$end_date = date("Y-m-d 23:59", strtotime("+{$data['period']} day", strtotime($start_date)));
	
	$row = array(
		'msg_text' => $data['msg'],
		'uid' => $_SESSION['jaram_user_uid'],
		'target_gid' => $data['gid'],
		'target_pid' => $data['pid'],
		'msg_type' => $data['msg_type'],
		'start_date' => $start_date,
		'end_date' => $end_date,
		'reg_date' => date("c")
	);
    getLogger()->debug($row);

	if (!empty($data['msg_id'])) {
		$where = $dbo->quoteInto("msg_id=?", $data['msg_id']);
		$result = $dbo->update('jaram_one_line', $row, $where);
	} else {
		$result = $dbo->insert('jaram_one_line', $row);
	}

	if ($result && $data['msg_id']) {
		echo "정상적으로 수정되었습니다";
	} else if ($result) {
		echo "정상적으로 입력되었습니다";
	} else {
		echo "오류가 발생했습니다";
	}
	p_redirect("./?page=one_line&action=list");
}

function one_line_list($data) {
    global $CONFIG;
	$smarty = new JaramSmarty();
	$dbo = ZendDB::getDBO();

	// 20%의 확률로 기간지난 메시지를 del처리하는 루틴을 호출
	if (rand(1,10) <= 2) {
		$dbo->query("UPDATE jaram_one_line SET msg_type='die' WHERE end_date < NOW()");
	}

	$msg = array();

	if (isset($data['msg_id'])) {
		$msg = $dbo->fetchRow("SELECT * FROM jaram_one_line WHERE msg_id=:msg_id", array('msg_id' => $data['msg_id']));
		$msg['period'] = (abs(strtotime($msg['end_date'])-strtotime($msg['start_date']))/86400)+1;
		$smarty->assign("notice", "{$data['msg_id']}번 글을 수정합니다");
	} else {
		// 자람 회원을 기본으로 설정;
		$msg['target_gid'] = 1002;
		$smarty->assign("notice", "한 줄 메시지를 추가합니다");
	}

	$msgs = $dbo->fetchAll("SELECT * FROM jaram_one_line WHERE uid=:uid ORDER BY start_date DESC, msg_id DESC", array('uid'=> $_SESSION['jaram_user_uid']));

	require_once 'Pager.php';
	
	$params = array(
		'mode'       => 'Sliding',
		'perPage'    => 10,
		'delta'      => 10,
		'itemData'   => $msgs
	);

    require_once 'Pager/Sliding.php';
	$pager =& Pager::factory($params);

    $program_list = ZendDB::getDBO()->fetchPairs("SELECT program_id, IF(LENGTH(sub_menu)=0, main_menu, CONCAT('└ ', sub_menu)) AS program_name FROM jaram_programs ORDER BY  main_menu ASC, order_num ASC");

    $group_list = ZendDB::getDBO()->fetchPairs("SELECT gid, group_name FROM jaram_groups  WHERE gid NOT BETWEEN :group_private_start AND :group_private_end ORDER BY gid ASC", array('group_private_start' => $CONFIG->group_private_start, 'group_private_end' => $CONFIG->group_private_end));

	$links = $pager->getLinks();
	$data  = $pager->getPageData();
	$smarty->assign("msgs", $data);
	$smarty->assign("msg", $msg);
	$smarty->assign("period", 1);
	$smarty->assign("links", $links);
	$smarty->assign("msg_type_options", array('info'=>'공지','error'=>'에러', 'warn'=>'경고'));
	$smarty->assign("group_options", $group_list);
	$smarty->assign("program_options", $program_list);
	$smarty->display('front/one_line_list.tpl');
}
