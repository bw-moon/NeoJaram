<?php
require_once('library.inc.php');

class JaramTag extends JaramCommon {
	
    function __construct ($tag_id = null) {
        parent::__construct();
		$this->tag_id = $tag_id;
    }

	function getTag($tag_id = null) {
		if ($tag_id) {
			
		} else if ($this->tag_id) {
			$tag_id = $this->tag_id;
		} else {
			return "";
		}

		return $this->dbo->fetchRow("SELECT * FROM jaram_tag_use AS a LEFT JOIN jaram_tags AS b ON a.tag_id=b.tag_id WHERE tag_use_id=:id", array('id'=>$tag_id));
	}

	function getTagName($tag_id = null) {
		$tag = $this->getTag($tag_id);
		return $tag['tag_name'];
	}

	function getTagList($tag_use_field) {
		$list = $this->dbo->fetchAll("SELECT * FROM jaram_tag_use AS a LEFT JOIN jaram_tags AS b ON a.tag_id=b.tag_id WHERE a.uid=:uid AND tag_use_field=:use_field", array('uid'=>$_SESSION['jaram_user_uid'], 'use_field'=>$tag_use_field));
		return $list;
	}

	function getAutoCompleteList($tag_name, $tag_type) {
		$list = $this->dbo->fetchAll("SELECT * FROM jaram_tags WHERE tag_type=:type AND LOWER(tag_name) LIKE :tag_name", array('type'=>$tag_type, 'tag_name'=>strtolower(trim($tag_name))."%"));
		return $list;
	}

	function saveTag($tag_name, $tag_type, $tag_use_field) {
		$tag_id = $this->dbo->fetchOne("SELECT tag_id FROM jaram_tags WHERE tag_type=:type AND LOWER(tag_name) LIKE :tag_name", array('type'=>$tag_type, 'tag_name'=> trim(strtolower($tag_name))));

		if (!$tag_id) {
			$tag_id = $this->insertTag($tag_name, $tag_type);
		}

		$this->dbo->insert('jaram_tag_use', array('tag_id'=>$tag_id, 'tag_use_field'=>$tag_use_field, 'uid' => $_SESSION['jaram_user_uid']));
		return $this->dbo->lastInsertId();
	}

	function insertTag($tag_name, $tag_type) {
		$this->dbo->insert('jaram_tags', array('tag_name'=>$tag_name, 'tag_type'=>$tag_type, 'tag_reg_date'=>date("Y-m-d"), 'tag_reg_uid'=>$_SESSION['jaram_user_uid']));
		return $this->dbo->lastInsertId();
	}

	function deleteTag($tag_use_id) {
		return $this->dbo->delete('jaram_tag_use', $this->dbo->quoteInto("tag_use_id=?", $tag_use_id));
	}

}



?>