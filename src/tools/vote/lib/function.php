<?

/*************************************
 *			function.php							*
 *			@decription : all function			*
 *			@author : serue						*
 *************************************/

	// email check
	function email_check($email)
	{
		if (!eregi("^[^@ ]+@[A-Z0-9\-\.]+\.+[A-Z0-9\-\.]", $email)){
			return 0;   
        } else {
			return $email;
		}
	}

	 
	 //페이지 네비게이션 함수
	function page_list($total, $per, $page, $script="", $ext="")
	{
			// ext 보정
			if(strlen($ext))
			{
					$ext    = ereg_replace("^[ ]*\?[ ]*", "", $ext);
					if(!ereg("^&", $ext)) $ext = "&".$ext;
			}

			// 설정
			if($total<1) return "[ 글이 존재하지 않습니다. ]";
			if(!is_numeric($page) || $page<1) $page = 1; // now page
					elseif($page>$total) $page = $total;
			$start=($per*floor(($page-1)/$per))+1; // start page

			// 처음, 이전
			if($start>1)
			{
					$return .= "<a href=\"".$script."?page=1".$ext."\" class=navside>[1]</a> ";
					$return .= "<a href=\"".$script."?page=".($start-1).$ext."\" class=navside><b>&nbsp;&lt;&lt;&nbsp;prev&nbsp;</b></a> ";
			}

			// 중간
			$end=$start + $per;
			for($i=$start; $i<$end && $i<=$total; ++$i)
			{
					$return .= "<a href=\"".$script."?page=".$i.$ext."\" class=navside>";
					if($i==$page) $return .= "<b>[".$i."]</b>";
							else $return .= "[".$i."]";
					$return .= "</a> ";
			}

			// 다음, 끝
			if($i<=$total)
			{
					$return .= "<a href=\"".$script."?page=".$i.$ext."\" class=navside><b>&nbsp;next&nbsp;&gt;&gt&nbsp;</b></a> ";
					$return .= "<a href=\"".$script."?page=".$total.$ext."\" class=navside>[".$total."]</a>";
			}

			return $return;
	}

?>