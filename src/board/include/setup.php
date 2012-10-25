<?
require_once realpath('../../lib/includes').'/library.inc.php';
if (!defined(WEB_DIR)) define(WEB_DIR, realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR);

function getmicrotime() 
{ 
   list($usec, $sec) = explode(" ", microtime()); 
   return ((float)$usec + (float)$sec); 
} 

function Dir_Delete($deleteDir)
{
	if (is_dir($deleteDir)) {
		$handle=opendir($deleteDir); 
		while (false!==($file = readdir($handle))) 
		{ 
			if ($file != "." && $file != "..")
			{ 
				unlink($deleteDir.DIECTORY_SEPARATOR.$file);
			} 
		}
		closedir($handle); 
		rmdir($deleteDir);
	}
}

// URL, Mail을 자동으로 체크하여 링크만듬
function autolink($str)
{
	global $agent,$rmail;

	$regex['file'] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
	$regex['file'] = "(\.({$regex['file']})\") TARGET=\"_blank\"";
	$regex['http'] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9,:;&#=_~%\[\]?\/.,+\-]+)([.]*[\/a-z0-9\[\]]|=[\xA1-\xFE]+))";
	$regex['mail'] = "([\xA1-\xFEa-z0-9_.-]+)@([\xA1-\xFEa-z0-9_-]+\.[\xA1-\xFEa-z0-9._-]*[a-z]{2,3}(\?[\xA1-\xFEa-z0-9=&\?]+)*)";

	# &lt; 로 시작해서 3줄뒤에 &gt; 가 나올 경우와
	# IMG tag 와 A tag 의 경우 링크가 여러줄에 걸쳐 이루어져 있을 경우
	# 이를 한줄로 합침 (합치면서 부가 옵션들은 모두 삭제함)
	$src[] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i";
	$tar[] = "<\\1\\2\\3>";
	$src[] = "/<([^<>\n]*)\n([^\n<>]*)>/i";
	$tar[] = "<\\1\\2>";
	$src[] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*({$regex['http']}|mailto:{$regex['mail']})[^>]*>/i";
	$tar[] = "<\\1 \\2=\"\\3\">";

	# email 형식이나 URL 에 포함될 경우 URL 보호를 위해 @ 을 치환
	$src[] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i";
	$tar[] = "\\1://\\2_HTTPAT_\\3";

	# 특수 문자를 치환 및 html사용시 link 보호
	$src[] = "/&(quot|gt|lt)/i";
	$tar[] = "!\\1";
	$src[] = "/<a([^>]*)href=[\"' ]*({$regex['http']})[\"']*[^>]*>/i";
	$tar[] = "<A\\1HREF=\"\\3_orig://\\4\" TARGET=\"_blank\">";
	$src[] = "/href=[\"' ]*mailto:({$regex['mail']})[\"']*>/i";
	$tar[] = "HREF=\"mailto:\\2#-#\\3\">";
	$src[] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*({$regex['http']})[\"']*/i";
	$tar[] = "<\\1\\2=\"\\4_orig://\\5\"";

	# 링크가 안된 url및 email address 자동링크
	$src[] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)({$regex['http']})/i";
	$tar[] = "\\1<A HREF=\"\\3\" TARGET=\"_blank\">\\3</a>";
	$src[] = "/({$regex['mail']})/i";
	$tar[] = "<A HREF=\"mailto:\\1\">\\1</a>";
	$src[] = "/<A HREF=[^>]+>(<A HREF=[^>]+>)/i";
	$tar[] = "\\1";
	$src[] = "/<\/A><\/A>/i";
	$tar[] = "</A>";

	# 보호를 위해 치환한 것들을 복구
	$src[] = "/!(quot|gt|lt)/i";
	$tar[] = "&\\1";
	$src[] = "/(http|https|ftp|telnet|news|mms)_orig/i";
	$tar[] = "\\1";
	$src[] = "'#-#'";
	$tar[] = "@";
	$src[] = "/{$regex['file']}/i";
	$tar[] = "\\1";

	# email 주소를 변형시킴
	$src[] = "/{$regex['mail']}/i";
	$tar[] = "\\1 at \\2";
//	$src[] = "/<A HREF=\"mailto:([^ ]+) at ([^\">]+)/i";
//	$tar[] = "<A HREF=\"act.php?o[at]=ma&target=\\1{$rmail['chars']}\\2";

	# email 주소를 변형한 뒤 URL 속의 @ 을 복구
	$src[] = "/_HTTPAT_/";
	$tar[] = "@";

	# 이미지에 보더값 0 을 삽입
	$src[] = "/<(IMG SRC=\"[^\"]+\")>/i";
	$tar[] = "<\\1 BORDER=0>";

	# IE 가 아닌 경우 embed tag 를 삭제함
	if($agent['br'] != "MSIE")
	{
	$src[] = "/<embed/i";
	$tar[] = "&lt;embed";
	}

	$str = preg_replace($src,$tar,$str);
	return $str;
}

/*
function autolink($text) { 

	$ret = eregi_replace("([[:alnum:]]+)://([a-z0-9\200-\377\\_\./~+@:?=%&,#\-]+)", "<a href=\"\\1://\\2\" target=\"_blank\">\\1://\\2</a>", $text); 
	$ret = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", "<a href='mailto:\\1' target=\"_self\">\\1</a>", $ret); 
	return $ret; 
}
*/

// 특수 문자열 파싱 and 카운팅 함수
// ex)
// <!--PU="ID값"-->조회수<!--PU-->
function pare_chage_number($filename,$pareString,$id,$addNumber)
{
	if (!file_exists($filename)) {
	 return -1;
	}

	$handle = fopen ($filename, "r");
	$contents = fread ($handle, filesize ($filename));
	fclose ($handle);

	preg_match_all ("/<!--".$pareString."=\"$id\"-->(.*)<!--".$pareString."-->/", $contents, $tmp);
	// 현 조회수 추출
	$numCount=$tmp[1][0]+$addNumber;
	// 변경된 조회수로 수정
	$contents=ereg_replace("<!--".$pareString."=\"$id\"-->[0-9]+<!--".$pareString."-->",
					"<!--".$pareString."=\"$id\"-->".$numCount."<!--".$pareString."-->",$contents);
	// 재수정된 캐시 저장
	$handle = fopen ($filename, "w");
	fwrite($handle,$contents);
	fclose ($handle);
}

// 이미지를 리사이즈 하여 저장하기
function copy_resize_img($filePath,$copypath,$wantWidth,$wantHeight,$imgtype)
{
//	echo "resize process 1<br>";
	$imgsize = @getimagesize($filePath);
	switch(strtolower($imgtype))
	{
		case "gif":
			$image=@imagecreatefromgif($filePath);
			break;
		case "jpg":
		case "jpeg":
			$image = @imageCreateFromJpeg($filePath);
			break;
		case "png":
			$image = @ImageCreateFromPNG($filePath);
			break;
		// 구분 할 수 없는 이미지 타입은 실패다.
		default:
			return false;
	}
//	echo "resize process 2<br>";
	if($imgsize[0]>$wantWidth || $imgsize[1]>$wantHeight)
	{
		if($imgsize[0]>$imgsize[1])
		{
			$wantimgsizey=$wantWidth*$imgsize[1]/$imgsize[0];
			$wantimgsizex=$wantWidth;
		}
		else
		{
			$wantimgsizex=$wantHeight*$imgsize[0]/$imgsize[1];
			$wantimgsizey=$wantHeight;
		}
	}
	$base_image = @imagecreatetruecolor($wantimgsizex,$wantimgsizey);
	// imagecopyresampled는 GD 버전이 2.x 이상이어야 한다.
	$check=@imagecopyresampled($base_image,$image,0,0,0,0,$wantimgsizex,$wantimgsizey, $imgsize[0],$imgsize[1]);
//	$check=@ImageCopyResized($base_image,$image,0,0,0,0,$wantimgsizex,$wantimgsizey, $imgsize[0],$imgsize[1]);
	if(!$check)
		return false;
	// png 지향한다.. ㅡㅡ;;
//	echo "resize process 3<br>";
	return @imagepng($base_image, $copypath);
}

// mySQL 관련 Class
class DB_mySql
{
	var $dbName;
	var $table;
	var $dbConnect;
	var $errorMsg;
	var $result;

	function DB_mySql()
	{
		require_once "info.php";
		if (!($this->dbConnect=mysql_connect($GLOBALS['CONFIG']->database->host,$GLOBALS['CONFIG']->database->username,$GLOBALS['CONFIG']->database->password)))
		{
			$this->errorMsg="DB Connect Error!(".mysql_errno().". ".mysql_error().")"; 
			return FALSE;
		}
		elseif(!mysql_select_db($GLOBALS['CONFIG']->database->dbname,$this->dbConnect))
		{
			$this->errorMsg="DB Select Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->dbName=$db;
		$this->errorMsg="";
		return TRUE;
	}

	function DB_connect($host, $user, $password, $dbName)
	{
		if (!($this->dbConnect=mysql_connect($host,$user,$password)))
		{
			$this->errorMsg="DB Connect Error!(".mysql_errno().". ".mysql_error().")"; 
			return FALSE;
		}
		elseif(!mysql_select_db($dbName,$this->dbConnect))
		{
			$this->errorMsg="DB Select Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->dbName=$dbName;
		$this->errorMsg="";
		return TRUE;
	}

	function DB_select($dbName)
	{
		if(!mysql_select_db($dbName,$this->dbConnect))
		{
			$this->errorMsg="DB Select Change Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->dbName=$dbName;
		$this->errorMsg="";
		return TRUE;
	}

	function query($query)
	{
		if (!($this->result=mysql_query($query,$this->dbConnect)))
		{
			$this->errorMsg="SQL Query Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="";
		return $this->result;
	}

	function fetch_array($query="")
	{
		if($query!="")
			if (!$this->query($query))
				return FALSE;

		if ($this->result)
		{
			if ($resultFetch=mysql_fetch_array($this->result))
			{
				$this->errorMsg="";
				return $resultFetch;
			}
			$this->errorMsg="Query -mysql_fetch_array- Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function fetch_row($query="")
	{
		if($query!="")
			if (!$this->query($query))
				return FALSE;

		if ($this->result)
		{
			if ($resultFetchRow=mysql_fetch_row($this->result))
			{
				$this->errorMsg="";
				return $resultFetchRow;
			}
			$this->errorMsg="Query -mysql_fetch_row- Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function fetch_object($query="")
	{
		if($query!="")
			if (!$this->query($query))
				return FALSE;

		if ($this->result)
		{
			if ($resultFetchObject=mysql_fetch_object($this->result))
			{
				$this->errorMsg="";
				return $resultFetchObject;
			}
			$this->errorMsg="Query -mysql_fetch_object- Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function sql_result($rowsNumber, $field)
	{
		if ($this->result)
		{
			if ($resultMysql=mysql_result($this->result, $rowsNumber, $field))
			{
				$this->errorMsg="";
				return $resultMysql;
			}
			$this->errorMsg="Mysql Result Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function number($query="")
	{
		if($query!="")
			if (!$this->query($query))
				return FALSE;

		if ($this->result)
		{
			if ($resultNumber=mysql_num_rows($this->result))
			{
				$this->errorMsg="";
				return $resultNumber;
			}
			$this->errorMsg="Query -mysql_num_rows- Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function seek($rowsNumber)
	{
		if($this->result)
		{
			if(mysql_data_seek($this->result, $rowsNumber))
			{
				$this->errorMsg="";
				return TRUE;
			}
			$this->errorMsg="Query -mysql_data_seek- Error!(".mysql_errno().". ".mysql_error().")";
			return FALSE;
		}
		$this->errorMsg="Result not exist";
		return FALSE;
	}

	function insert_id($query="")
	{
		if($query!="")
			if (!$this->query($query))
				return FALSE;

		if ($resultInsertId=mysql_insert_id($this->dbConnect) )
		{
			$this->errorMsg="";
			return $resultInsertId;
		}
		$this->errorMsg="Query -mysql_insert_id- Error!(".mysql_errno().". ".mysql_error().")";
		return FALSE;
	}

	function status($tableName,$kind)
	{
		$this->query("SHOW TABLE STATUS like '$tableName'");

		if ($resultStatus=$this->sql_result(0,$kind) )
		{
			$this->errorMsg="";
			return $resultStatus;
		}
		$this->errorMsg="Query -SHOW TABLE STATUS- Error!(".mysql_errno().". ".mysql_error().")";
		return FALSE;
	}

	function next_id($talbeID)
	{
		return $this->status($talbeID,'Auto_increment');
	}

	function close()
	{
		if(mysql_close($this->dbConnect))
		{
			return TRUE;
			$this->delete();
		}
		$this->errorMsg="DB Disconnect Error!(".mysql_errno().". ".mysql_error().")"; 
		return FALSE;
	}

	function delete()
	{
		unset($this->dbConnect);
		unset($this->result);
		unset($this->errorMsg);
	}

	function error()
	{
		return $this->errorMsg;
	}

};

?>