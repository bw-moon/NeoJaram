<?php
require_once('library.inc.php');

class JaramGroup extends JaramCommon {

    function __construct ($gid) {
        parent::__construct();
		$this->gid = $gid;
    }

    function getInfo($gid) {
        return $this->dbo->fetchRow("SELECT * FROM jaram_groups WHERE gid=:gid", array('gid'=>$gid));
    }

	function getGroupMember() {
		if ($this->gid) {
			$query = "SELECT * FROM jaram_users AS u LEFT JOIN jaram_user_group AS g ON u.uid = g.uid WHERE u.user_number > 0 AND  g.gid=:gid ORDER BY u.user_number, u.user_name";
			$param = array('gid' => $this->gid);
		} else {
			$query = "SELECT * FROM jaram_users AS u WHERE u.user_number > 0 ORDER BY u.user_number, u.user_name";
			$param = array();
		}
		return $this->dbo->fetchAll($query, $param);
	}

	function getGroupList() {
		if ($this->gid) {
			$query = "SELECT * FROM jaram_user_group AS a LEFT JOIN jaram_groups AS b ON a.gid=b.gid WHERE a.uid=:gid AND b.gid NOT BETWEEN :group_private_start AND :group_private_end  ORDER BY group_name";
			$params = array('gid'=>$this->gid, 'group_private_start' => $this->config->group_private_start, 
                'group_private_end' => $this->config->group_private_end);
			return $this->dbo->fetchAll($query, $params);
		} else {
			return $this->getCommonGroups();
		}
	}

    function getCommonGroups() {
        $sql = "SELECT 
            g.gid, g.group_description, g.group_name, u.id, u.status 
        FROM 
            jaram_groups AS g LEFT JOIN 
            jaram_user_group AS u 
        ON 
            g.gid=u.gid AND u.uid=:uid 
        WHERE 
            g.gid NOT BETWEEN :group_private_start AND :group_private_end ORDER BY (u.id IS NULL), u.status DESC , g.gid ASC";

        $param = array(
                'group_private_start' => $this->config->group_private_start, 
                'group_private_end' => $this->config->group_private_end, 
                'uid'=> $uid
            );

        return $this->dbo->fetchAll($sql, $param);
    }

	static function getGroupIdByName($name) {
		$dbo = ZendDB::getDBO();
		return $dbo->fetchOne("SELECT gid FROM jaram_groups WHERE group_name LIKE :group_name", array('group_name'=>trim($name)));
	}

    function getGroupOptions() {
        $sql = "SELECT gid,group_name FROM  jaram_groups WHERE gid NOT BETWEEN :group_private_start AND :group_private_end ORDER BY gid ASC";
        $param = array(
                'group_private_start' => $this->config->group_private_start, 
                'group_private_end' => $this->config->group_private_end, 
            );
        return $this->dbo->fetchPairs($sql, $param);

    }

    function getJoinWaitGroup($uid) {
        return $this->dbo->fetchAll("SELECT a.gid, a.group_name, a.group_description FROM jaram_groups AS a LEFT JOIN jaram_group_join_wait AS b ON a.gid=b.gid WHERE b.uid=:uid", array('uid'=>$uid));
    }

    function getAcceptWaitList($uid) {
        // TODO: 편의를 위해 대기중인 모든 사람을 보여주는데, 차후에 관리하는 그룹에 가입 대기하는 사람을 보여주도록
        return $this->dbo->fetchAll("SELECT u.uid, u.user_name, u.user_id, g.id, c.group_name, c.group_description, c.gid FROM jaram_users AS u RIGHT JOIN jaram_group_join_wait AS g ON u.uid = g.uid LEFT JOIN jaram_groups AS c ON g.gid=c.gid WHERE g.gid NOT BETWEEN :group_private_start AND :group_private_end", array('group_private_start' => $this->config->group_private_start, 'group_private_end' => $this->config->group_private_end));
    }

    function getNextId() {
        return $this->dbo->fetchOne("SELECT gid FROM jaram_group_pool WHERE flag='x' AND gid >= 3000 ORDER BY gid ASC LIMIT 1");
    }

    function isOwner($gid, $uid = 0) {
        $uid = $uid ? $uid : $_SESSION['jaram_user_uid'];
        $this->logger->debug("{$uid} {$gid}");
        return $this->dbo->fetchOne("SELECT status FROM jaram_user_group WHERE gid=:gid AND uid=:uid", array('uid'=>$uid, 'gid'=>$gid));
    }

    function insertGroup($group_name, $group_desc, $uid) {
        $this->dbo->beginTransaction();
        try {
            $gid = $this->getNextId();
            $insert_group_row = array('gid'=>$gid, 'group_name'=>$group_name, 'group_description'=>$group_desc);
            $this->dbo->insert('jaram_groups', $insert_group_row);
            
            $insert_user_group_row = array('gid'=>$gid, 'uid'=>$uid, 'status'=>'o');
            $this->dbo->insert('jaram_user_group', $insert_user_group_row);
            
            $update_group_pool_where = $dbo->quoteInto('gid=?', $gid);
            $this->dbo->update('jaram_group_pool', array('flag'=>'o'), $update_group_pool_where);

            $this->dbo->commit();
            return true;
        
        } catch (Exception $e) {
            $this->dbo->rollback();
            $this->logger->err($e->getMessage());
            return false;
        }
    }

    function updateGroup($gid, $group_name, $group_desc, $uid) {
        if ($this->isOwner($gid, $uid)) {
            $this->logger->debug('그룹 관리자가 맞음 gid:'.$gid);
            $set = array('group_name'=>$group_name, 'group_description'=>$group_desc);
            $where = $this->dbo->quoteInto('gid=?', $gid);
            return $this->dbo->update('jaram_groups', $set, $where);
        }
        return false;
    }


    function acceptJoinGroup($gid, $uid) {
        $this->dbo->beginTransaction();
        try {
            if ($this->isOwner($gid)) {
                $this->dbo->insert('jaram_user_group', array('uid'=>$uid, 'gid'=>$gid, 'status'=>''));
                $this->dbo->delete('jaram_group_join_wait', $this->getWhere($uid, $gid));
            }
        } catch (Exception $e) {
            $this->dbo->rollback();
            $this->logger->err($e->getMessage());
            return false;
        }
    }


    function cancelJoinGroup($gid, $uid) {
        return $this->dbo->delete('jaram_group_join_wait', $this->getWhere($uid, $gid));
    }


    function getWhere($uid, $gid) {
        $where = $this->dbo->quoteInto('uid=?', $uid);
        $where .= ' AND '.$this->dbo->quoteInto('gid=?', $gid);
        return $where;
    }

    function isJoinable($gid, $uid) {
        // 이미 가입되어 있을 경우 에러
		$result = mysql_query("SELECT count(*) AS num FROM jaram_user_group WHERE gid='".$_GET['gid']."' AND uid='".$_SESSION['jaram_user_uid']."';");
		$join_count = mysql_fetch_array($result);

		// 이미 신청되어 있을 경우 에러
		$result = mysql_query("SELECT count(*) AS wait FROM jaram_group_join_wait WHERE uid='".$_SESSION['jaram_user_uid']."' AND gid='".$_GET['gid']."';");
		$wait_count = mysql_fetch_array($result);
    }
}

?>