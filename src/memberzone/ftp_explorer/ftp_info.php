<?
/****************************************
 *	Author :
 *		Hanyang Univ. EECS.
 *		Jaram 17th
 *		김성경(lovelyk2)
 *	
 *	Start Day	: 2003-03-07
 *	End Day	: 
 *
 *	E-mail	: lovelyk2@jaram.org
 *	File Path	: ~webteam/public_html/member_zone/ftp_explorer
 *	File Name	: ftp_conn.php
 ****************************************/

class ftp_info
{
	var $ftp_conn;			// ftp connection
	var $cur_dir;			// current directory
	var $file_list;			// recived file list

	function init($ftp_conn, $cur_dir)
	{
		$this->ftp_conn = $ftp_conn;
		$this->cur_dir = $cur_dir;
	}

	function pwd()
	{
		return ftp_pwd($this->ftp_conn);
	}

	function raw_list()
	{
		$this->file_list = @ftp_rawlist($this->ftp_conn, urldecode($this->cur_dir));
	}

	function ftp_fetch_array()
	{
		if( !(list($key, $value)=each($this->file_list)) )
			return false;					// no more file_lists

		list($ftp_fields["last_date"], $ftp_fields["last_time"], $ftp_fields["res_type"], $ftp_fields["res_name"])
			= split("[[:space:]]+", $value, 4);

		if( eregi("<dir>", $ftp_fields["res_type"]) )
		{
			$ftp_fields["res_type"] = "dir";
			$ftp_fields["res_size"] = 0;
		}
		else
		{
			$ftp_fields["res_size"] = $ftp_fields["res_type"];
			$ftp_fields["res_type"] = "file";
		}

		$ftp_fields["res_name"] = eregi_replace("\.?/", "", $ftp_fields["res_name"]);

		return $ftp_fields;
	}

	function set_dir($dir)
	{
		$this->curdir = $dir;
	}

	function set_ftp_server($ftp_conn)
	{
		$this->ftp_conn = $ftp_conn;
	}
}


?>






















