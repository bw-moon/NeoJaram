<?php
require_once('library.inc.php');

class JaramBoard extends JaramCommon {
    private $bid;
    private $real_upload_dir;
    private $temp_upload_dir;

    function JaramBoard($bid = 'dual') {
        parent::__construct();
        $this->bid = $bid;
        $this->real_upload_dir = PROGRAM_ROOT.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR.$bid;
        $this->temp_upload_dir = PROGRAM_ROOT.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."temp";
    }

    function getComments($id) {
        chk_auth('view|list');

        $comments = $this->dbo->fetchAll("SELECT a.*,b.title,b.bid,c.user_name,UNIX_TIMESTAMP(a.date)-UNIX_TIMESTAMP(b.date) AS time_gap,c.user_having_image2  FROM jaram_board_comment AS a LEFT JOIN jaram_board AS b ON a.subid=b.id LEFT JOIN jaram_users AS c ON a.usrid=c.uid WHERE b.bid=:bid AND subid=:id ORDER BY a.date ASC", array('bid'=>$this->bid, 'id'=>$id));
        return $comments;
    }


    function getRecentComments($day = 1) {
        chk_auth('view|list');

        $comments = $this->dbo->fetchAll("SELECT a.*,b.title,b.bid,c.user_name,UNIX_TIMESTAMP()-UNIX_TIMESTAMP(a.date) AS time_gap FROM jaram_board_comment AS a LEFT JOIN jaram_board AS b ON a.subid=b.id LEFT JOIN jaram_users AS c ON a.usrid=c.uid WHERE b.bid=:bid AND a.date >= DATE_ADD(CURDATE(), INTERVAL -{$day} DAY) ORDER BY a.date DESC LIMIT 5", array('bid'=>$this->bid));

        return $comments;
    }

    function getTotalRecentComments($day = 1) {
        chk_auth('view|list');

        $comments = $this->dbo->fetchAll("SELECT a.*,CONCAT(d.main_menu,' : ',b.title) AS title,b.bid,c.user_name,UNIX_TIMESTAMP()-UNIX_TIMESTAMP(a.date) AS time_gap FROM jaram_board_comment AS a LEFT JOIN jaram_board AS b ON a.subid=b.id LEFT JOIN jaram_users AS c ON a.usrid=c.uid LEFT JOIN jaram_programs AS d ON b.bid=d.bid WHERE a.date >= DATE_ADD(CURDATE(), INTERVAL -{$day} DAY) ORDER BY a.date DESC LIMIT 5");
        return $comments;
    }
    
    function getDraftArticles($global = false) {
        if ($global) {
            return $this->dbo->fetchAll("SELECT * FROM jaram_board_draft WHERE usrid=:uid ORDER BY date DESC", array('uid'=>$_SESSION['jaram_user_uid']));
        } else {
           return $this->dbo->fetchAll("SELECT * FROM jaram_board_draft WHERE usrid=:uid AND bid=:bid ORDER BY date DESC", array('uid'=>$_SESSION['jaram_user_uid'], 'bid'=>$this->bid));
        }
    }

    function getDraftArticle($id) {
        return $this->dbo->fetchRow("SELECT * FROM jaram_board_draft WHERE id=:id", array('id'=>$id));
    }

    function getNextId(	) {  
        return  $this->dbo->fetchOne("SELECT MAX(id)+1 FROM jaram_board");
    }

    function getNextLocate() {  
        return  $this->dbo->fetchOne("SELECT MAX(locate)+1 FROM jaram_board WHERE bid=:bid", array('bid'=>$this->bid));
    }


	function makeSortQuery($sort, $asc, $table = "") {
		$valid_sort_field = array('date', 'locate', 'count');

		if ($table) {
			$sort = "{$table}.{$sort}";
		}

		$order = ($asc) ? "ASC" : "DESC";

		if (!in_array($sort, $valid_sort_field)) {
			$sort = "locate";
		}

		return "{$sort} {$order}";
	}


	function makeSearchQuery($search_types, $search_value, $table = "") {
		$valid_search_type = array ('note', 'name', 'title', 'comment');

		$query = "";

		$query_token = array();

		if (is_array($search_types)) {
			foreach ($search_types as $type) {			
				$field = ($table) ? "{$table}.{$type}" : $type;

				switch ($type) {
					case "comment":
						break;
					default:
						$query_token[] = $this->dbo->quoteInto("{$field} LIKE ?", "%{$search_value}%");
				}
			}
		}

		return implode(" OR ", $query_token);
	}

	function makeCategoryQuery($category, $table = "") {
		$field = ($table) ? "{$table}.category" : "category";
	
		if ($category) {
			return $this->dbo->quoteInto("{$field}=?", $category);
		}
	}

	function getCategoryList($bid) {
		return $this->dbo->fetchAll("SELECT * FROM jaram_board_category WHERE bid=:bid", array("bid"=>$bid));
	}

	function getTotalCount($post_data) {
		$query_sort = $this->makeSortQuery($post_data['sort'], $post_data['asc']);
		$query_search = $this->makeSearchQuery($post_data['search_type'], $post_data['search_value']);
		$query_category = $this->makeCategoryQuery($post_data['category']);

		if ($query_search && $query_category) {
			$where = "AND ({$query_search}) AND {$query_category}";
		}
		else if ($query_search || $query_category) {
			$where = "AND ({$query_search} {$query_category})";
		}
		else {
			$where = "";
		}

		$sql = "SELECT COUNT(*) FROM jaram_board WHERE bid=:bid {$where}";

		return $this->dbo->fetchOne($sql, array('bid' => $post_data['tableID']));
	}

	function getArticleList($post_data, $start, $limit, $type = "default") {
		
		if ($type = "expend") {
			$field_list = array("*");
		} else {
			$field_list = array('id', 'title', 'name', 'usrid', 'count', 'comment_count');
		}
		
		$to_get_field = implode(", ", $field_list);

		$query_sort = $this->makeSortQuery($post_data['sort'], $post_data['asc']);
		$query_search = $this->makeSearchQuery($post_data['search_type'], $post_data['search_value']);
		$query_category = $this->makeCategoryQuery($post_data['category']);

		if ($query_search && $query_category) {
			$where = "AND ({$query_search}) AND {$query_category}";
		}
		else if ($query_search || $query_category) {
			$where = "AND ({$query_search} {$query_category})";
		}
		else {
			$where = "";
		}

		$sql = "SELECT {$to_get_field} FROM jaram_board WHERE bid=:bid {$where} ORDER BY depth < 0 DESC, {$query_sort}, sortno ASC LIMIT {$limit} OFFSET {$start}";

		$article_list = $this->dbo->fetchAll($sql, array('bid' => $post_data['tableID']));

		// 각 게시물 처리
		for ($i = 0; $i < sizeof($article_list); $i++)
		{
			$article_list[$i]['title'] = stripslashes($article_list[$i]['title']);

			// 마이그레이션을 위한 임시코드 start
			if (!$article_list[$i]['comment_count']) {
				$article_list[$i]['comment_count'] = $this->dbo->fetchOne("SELECT COUNT(*) from jaram_board_comment WHERE subid=:subid", array('subid'=>$article_list[$i]['id']));
				$this->dbo->update("jaram_board", array('comment_count'=>$article_list[$i]['comment_count']), "id={$article_list[$i]['id']}");
			}
			if (!$article_list[$i]['name']) {
				$article_list[$i]['name'] = $this->dbo->fetchOne("SELECT user_name from jaram_users WHERE uid=:uid", array('uid'=>$article_list[$i]['usrid']));
				$this->dbo->update("jaram_board", array('name'=>$article_list[$i]['name']), "id={$article_list[$i]['id']}");
			}
			// 마이그레이션을 위한 임시코드 end

			$article_list[$i] = $this->highlightSearchResult($post_data, $article_list[$i]);
		}
		return $article_list;
	}

	function getArticle($post_data) {

		$article = $this->dbo->fetchRow("select a.*, c.user_name, c.user_having_image1 from jaram_board as a LEFT JOIN jaram_users as c ON a.usrid=c.uid where id=:id", array('id'=>$post_data['id']));
		$article = $this->highlightSearchResult($post_data, $article);
		return $article;		
	}

	function highlightSearchResult($post_data, $row) {
		// 검색어 강조
		if ($post_data['search_value']) {
			foreach ($post_data['search_type'] as $search_type) {
				$row[$search_type] = str_replace($post_data['search_value'], "<strong class=\"search\">{$post_data['search_value']}</strong>", $row[$search_type]);
			}
		}
		return $row;
	}

    function deleteComment($id) {
        chk_auth('delete');

        $where = $this->dbo->quoteInto('id=?', $id);
        return $this->dbo->delete('jaram_board_comment', $where);
    }

    function saveDraftArticle($post_data) {
        chk_auth('write');

        $row = array(
            'title' => $post_data['title'], 
            'note' => $post_data['note'],
            'bid' => $this->bid,
            'usrid' => $_SESSION['jaram_user_uid'],
            'date' => date('c')
            );
        
        if ($post_data['id'] > 0) {
            $this->logger->debug("글을 수정 : {$post_data['id']}");
            $where = $this->dbo->quoteInto('id=?', $post_data['id']);
            $this->dbo->update('jaram_board_draft', $row, $where);
            return $post_data['id'];
        } else {
            $this->logger->debug("글을 새롭게 생성");
            $this->dbo->insert('jaram_board_draft', $row);
            return $this->dbo->lastInsertId();
        }
    }

    function cleanDraftArticle($id) {
        $where = $this->dbo->quoteInto('id=?', $id);
        return $this->dbo->delete('jaram_board_draft', $where);
    }


    function replyArticle($post_data) {
        chk_auth('reply');

		// 답변관련된 정보를 얻기
		$parent_article = $this->dbo->fetchRow("SELECT locate,depth,sortno from jaram_board WHERE id=:id", array('id'=>$post_data['replyID']));

		$locate = $parent_article['locate'];
		$depth = $parent_article['depth'];
		$sortno = $parent_article['sortno'];
		
		// 사이에 낄 답변을 위해 아래 답변의 sortno를 1증가
		$this->dbo->query("UPDATE jaram_board SET sortno=sortno+1 WHERE locate = :locate AND sortno > :sortno ", array('locate'=>$locate, 'sortno'=>$sortno));

		$depth = ($depth<0) ? 1 : $depth+1;
		$sortno++;

        $row = $this->getRowData($post_data);
        $row['depth'] =  $depth;
		$row['locate'] = $locate;
		$row['sortno'] = $sortno;

        $rows_affected = $this->dbo->insert('jaram_board', $row);
		$row['id'] = $this->dbo->lastInsertId();

        $post_data['id'] = $row['id'];
        $this->doFileUpload($post_data);

        $this->cleanDraftArticle($post_data['draft_id']);

        $this->notifyMonitor($row);
        return $row['id'];
    }

    function updateArticle($post_data) {
        chk_auth('modify');
        $row = $this->getRowData($post_data);

        $row['depth'] =  ($post_data['always']) ? -1 : 0;

        unset($row['locate']);
		unset($row['sortno']);
		unset($row['date']);
        unset($row['bid']);

        $where = $this->dbo->quoteInto('id=?', $post_data['modID']);
		$rows_affected = $this->dbo->update('jaram_board', $row, $where);
        
        $post_data['id'] = $row['id'];
        $this->doFileUpload($post_data);

        $this->cleanDraftArticle($post_data['draft_id']);

    }

    function postArticle($post_data) {
        chk_auth('post');

        $row = $this->getRowData($post_data);
        $row['depth'] =  ($post_data['always']) ? -1 : 0;
		$row['locate'] = $this->getNextLocate();
		$row['sortno'] = 0;

        $rows_affected = $this->dbo->insert('jaram_board', $row);
		$row['id'] = $this->dbo->lastInsertId();
        
        $post_data['id']  = $row['id'];
        $this->doFileUpload($post_data);

        $this->cleanDraftArticle($post_data['draft_id']);
        $this->notifyMonitor($row);
        return $row['id'];
    }

	function deleteArticle($post_data) {
		chk_auth('delete');

		$this->dbo->delete("jaram_board", "id='{$_POST['id']}'");

		$uploaded_files = $this->dbo->fetchAll("SELECT * FROM jaram_board_file where sub_id=:id");

		// 파일 삭제
		foreach ($uploaded_files as $file) {
			@unlink($this->real_upload_dir."/".$file['file_link']);
		}

		// 파일 레코드 삭제
		$this->dbo->delete("jaram_board_file", "sub_id='{$_POST['id']}'");
	
		// 코멘트 삭제
		$this->dbo->delete("jaram_board_comment", "subid='{$_POST['id']}'");
	}


    function notifyMonitor($row) {
        // 게시판 모니터링
		$programData=get_program_info();
		$monitorData['pid']=$programData['pid'];
		$monitorData['bid']=$programData['bid'];
		$monitorData['id']=$row['id'];
		$monitorData['subject']=$row['title'];
		$monitorData['author']=($row['name']) ? $row['name']: $_SESSION["jaram_user_name"];
		$monitorData['date']=time();
		monitor_bbs($monitorData);
    }

    function getRowData($post_data) {
        $row = array(
            'title' => stripcslashes($post_data['title']),
            'bid' => $post_data['tableID'],
            'category' => $post_data['kind'],
            'name' => $post_data['name'],
            'usrid' => $_SESSION["jaram_user_uid"],
            'password' => md5($post_data['passwd']),
            'email' => $post_data['email'],
            'homepage' => $post_data['homepage'],
            'note' => stripcslashes($post_data['note']),
            'file_number' => 0,
            'file_size' => 0,
            'date'=>date("c"),
            'host' => getenv("REMOTE_ADDR"),
            'locate' => 0,
            'depth' => 0,
            'sortno' => 0,
            'extend1'=>$post_data['extend1'],
            'extend2'=>$post_data['extend2']
        );
        return $row;
    }

    function doFileUpload($post_data) {
        chk_auth('upload');

        $d = DIRECTORY_SEPARATOR;

        if (!file_exists($this->real_upload_dir)) {
            mkdir($this->real_upload_dir);
        }

        // 업로드 파일 정보를 해석
        parse_str($post_data['update_file_list'], $files_to_update);

/*
        $total_file_size = 0;
        foreach($files_to_update as $key => $value) {
            $file_info_token = explode("-", $key);
            if (count($file_info_token) == 2) {
                $total_file_size += $file_info_token[1];
            }
        }
*/

        // temp폴더에서 upload의 tableID 폴더로 이동
        $uploadFileList = explode (",", $post_data["files_to_change"]);
        $this->logger->debug($post_data);

        foreach ($uploadFileList as $file_info)
        {	
            $uploadLinkName= $this->real_upload_dir.$d.md5(uniqid());
            $file_info_token = explode(":", $file_info);
            $file_name_token = explode("-", $file_info_token[0]);

            if (count($file_info_token) == 2 && count($file_name_token) == 2) {
                $temp_file_name = $this->temp_upload_dir.$d.$file_name_token[0];

                if ($file_info_token[1] == "insert") {
                    if (rename($temp_file_name, $uploadLinkName))
                    {
                        $this->logger->debug($files_to_update);
                        // Data 정리
                        $trueName=str_replace(" ", "_", trim(strstr($files_to_update[$file_info_token[0]], " ")));
                        $fileSize=$file_name_token[1];
                        $linkName=basename($uploadLinkName);

                        $row = array ('file_name'=>$trueName,
                            'file_link'=>$linkName,
                            'file_size'=>$fileSize,
                            'sub_id'=>$post_data['id'],
                            'file_date'=>date("c")
                            );
                        // DB 기록
                        $this->dbo->insert('jaram_board_file', $row);
                    }
                } else if ($file_info_token[1] == "delete") {
                    // 임시로 업로드된 파일을 지우는 경우
                    if (file_exists($temp_file_name)) {
                        unlink($temp_file_name);
                        $this->logger->debug("임시로 업로드 되었던 파일 삭제 : ".$temp_file_name);
                    } 
                    // 글을 수정하는 과정에서 이전에 업로드 된 파일을 지우는 경우
                    else {
                        $this->logger->debug("업로드 된 파일 정보를 가져움 : ".$file_name_token[0]);
                        $fileRow = $this->dbo->fetchRow("SELECT * FROM jaram_board_file WHERE file_id=:file_id", array('file_id'=>$file_name_token[0]));
                        
                        // 일단 DB정보를 삭제
                        $where = $this->dbo->quoteInto('file_id=?', $fileRow['file_id']);
                        $this->dbo->delete('jaram_board_file', $where);

                        // 파일을 삭제
                        if (!unlink($this->real_upload_dir.$d.$fileRow['file_link'])) {
                            $this->logger->err('파일 삭제 불가 : '.$this->real_upload_dir.$d.$fileRow['file_link']);
                        }
                    }
                }
            }
        }
    }
}
?>