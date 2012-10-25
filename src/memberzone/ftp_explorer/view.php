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
 *	File Name	: view.php
 ****************************************/

	// ftp info class
	include "./ftp_info.php";
	// ftp 계정 인증
	include "../../../../auth/ftp_conn.php";
	// html header
	//	include "header.html";

	////////////////////////////////////////////
	//	include 자람 회원 인증!
	//	임시로 IP로 인증 --;;
	if( substr($REMOTE_ADDR, 0, -3) != "166.104.223" )
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;url=http://jaram.org/member_zone/\">";
	}
	//
	/////////////////////////////////////////////	

/*
	// for test
	if( $list = ftp_nlist ($ftp_conn, "/개발 관련") )
	{
	}
	else
	{
		echo "<script>alert(\"error\");</script>";
	}
/*  */

	if( $dir == "" )				// dir 변수 초기값
		$dir = "/";
	if( !ftp_chdir($ftp_conn, $dir) )		// 디렉토리가 존재하지 않으면
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;url=http://jaram.org/member_zone/\">";
	}

	$ftp = new ftp_info;			// 객체 생성
	$ftp->init($ftp_conn, ftp_pwd($ftp_conn));	// 객체에 필요한 정보 초기화
	$ftp->raw_list();

	// header
	echo "<table>\n";
	echo "\t<tr>\n";
	echo "\t\t<td colspan=\"4\">현재디렉토리 : " . $ftp->pwd() . "</td>\n";
	echo "\t</tr>\n";

	if( substr($dir, -1) == "/")
		$dir = substr($dir, 0, -1);

	$pre_dir = substr($dir, 0, strrpos($dir, "/"));

	echo "\t<tr>\n";
	echo "\t\t<td colspan=\"4\"><a href=\"${PHP_SELF}?dir=" . rawurlencode($pre_dir) . "\">../</a></td>\n";
	echo "\t</tr>\n";

	// main loop
	while( $field = $ftp->ftp_fetch_array() )
	{
		echo "\t<tr>\n";
		// highlight
		//$last_modify_time = mktime( substr($field["last_time"]
		if( $field["res_type"] == "dir" )		// 디렉토리이면
		{
			echo "\t\t<td><a href=\"${PHP_SELF}?dir=" . rawurlencode($dir."/".$field["res_name"]) . "\">" . htmlspecialchars($field["res_name"]) . "</a></td>\n";
			echo "\t\t<td>&nbsp;</td>\n";
			echo "\t\t<td>" . $field["last_date"] ."&nbsp;" . $field["last_time"] . "</td>\n";
		}
		else if( $field["res_type"] == "file" )
		{
			echo "\t\t<td>" . htmlspecialchars($field["res_name"])."</td>\n";				// 파일 명 출력
			echo "\t\t<td>" . ($field["res_size"]/1048576) . " KB</td>\n";		// 1048576 == 1024 * 1024
			echo "\t\t<td>" . $field["last_date"] ."&nbsp;" . $field["last_time"] . "</td>\n";
		}
		else
		{
			echo "\t<tr>\n";
			echo "\t\t<td colspan=\"4\">Unknown type -_-;;</td>\n";
			echo "\t</tr>\n";
		}
		echo "\t</tr>\n";
	}
	echo "</table>\n";
?>

