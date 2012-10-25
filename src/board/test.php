<?
require "include/setup.php";
include "include/info.php";

$M_TABLE="mboard_diary";
$M_TABLE_RE="mboard_diary_re";

$dbList= new DB_mySql;
$dbList->query("SELECT * FROM $M_TABLE AS a LEFT JOIN $M_TABLE_RE AS b ON a.id=b.subid");
$numberList=$dbList->number();
for($tempCount=0;$tempCount<$numberList;$tempCount++)
{
	$dbList->seek($tempCount);
	$categoryData[$tempCount]=$dbList->fetch_object();
}




exit;

echo "<img src=\"/imageview.php?Image=a.jpg\" border=0>";

require "include/setup.php";
include "include/info.php";

$B_TABLE="jaram_study";
$M_TABLE="mboard_newstips";
$M_TABLE_FILE="mboard_newstips_file";
$M_TABLE_RE="mboard_newstips_re";

$dbConnect=mysql_connect("localhost","webmaster","vnfjdqhsh");
mysql_select_db("jaram",$dbConnect);

	function PrintCommentForm($row,$usrid,$subid,$date)	{
	global $dbConnect,$tempList,$M_TABLE_RE;

		$comment=explode("!t!",stripslashes($row[comment])); // 답변글 쪼개기 			
		
		for($i=0 ; $comment[$i] != null ; $i++)
		{
			// 한 레코드를 필드별로 쪼갠다
			$data=explode('!n',$comment[$i]);
			$data[1] = nl2br(htmlspecialchars($data[1]));
			
			$date=DateChange($date,$data[2]);

//			echo "$data[0]&nbsp;&nbsp;$data[1]&nbsp;&nbsp;$data[2]<br/>";

			mysql_query("INSERT INTO $M_TABLE_RE VALUES ('', '$data[0]', '$usrid', '$data[1]', '', '$date', '$subid')",$dbConnect);

		}


	} // end of PrintCommentForm		

function DateChange($date,$commentDate)
{
	$tempReturn=substr($date,0,4);
	$tempReturn.="-".substr($commentDate,1,2)."-".substr($commentDate,4,2)." ".substr($commentDate,7,2).":".substr($commentDate,10,2).":00";
	return $tempReturn;
}

function PrintReply($td,$replyDepth=1) {							
	global $dbConnect,$tempList,$M_TABLE,$replyLocate;
	$re=explode(",",$td[son]); // 답변글 쪼개기
		for($i=0 ; $re[$i] != null ; $i++) {
			$replyResult=mysql_query("select * from $B_TABLE where id = $re[$i] order by id desc");
	
			$row=mysql_fetch_array($replyResult); 

			if($row[name] == null) continue; // 답변글이 지워졌으면 출력 안함

			// 답글의 답글의 공백을 계산
			/*
			for($j=0;$j<$replyDepth;$j++) {
				// 빈칸을 제목에 합친다.
				$space="&nbsp;&nbsp;&nbsp;&nbsp;"; 
				$row[subject]=$space.$row[subject];
			}
			*/

			/* 글 짜르기 노가다부분 */
			$b_loc=strrpos($row[subject],"&nbsp;");

			// 제목이 길때 ... 표시 
			/*
			if( (strlen($row[subject])-$b_loc) >40){
				$b_loc -=$replyDepth;
				$row[subject]=substr($row[subject],0,44+$b_loc)."...";} 
			*/
			$tempList++;

			if($row[filename])
				$returnFile=FileChange($td[filename],$replyLocate,$row[date]);

			$replySortno=$tempList;
			mysql_query("INSERT INTO $M_TABLE VALUES ('', '$row[subject]', '', '$row[name]', '$row[userid]', '$row[passwd]', '$row[email]', '$row[homepage]', '$row[passage]', '$returnFile[0]','$returnFile[1]', '$row[date]','$row[host]', '$row[count]', '$replyLocate', '$replyDepth', '$replySortno')",$dbConnect);
			// 출력폼 
//			echo "<font color='red'>$row[id] &nbsp;&nbsp; $row[subject] ($replyDepth) ($tempList)</font><br>";
			PrintCommentForm($row,$row[userid],$replyLocate,$row[date]);
		
			// 답글의 답글이 있을 때 재귀호출
			if($row[son]) 
				PrintReply($row,$replyDepth+1);
	} // end of for sentence.
}	// end of reply()

function FileChange($filename,$nextID,$date)
{
	global $dbConnect,$tempList,$M_TABLE_FILE;
	
	$UPLOAD_DIR="/home/jaram/webteam/public_html/jaram/board/upload/discussion/";
	$DOWNLOAD_DIR="/home/httpd/html/member_zone/files/";
	$returnFile[0]=0;
	$returnFile[1]=0;

	$fileList=explode(",",$filename); // 파일 이름 쪼개기

	if ($fileList[0])
	{
		$newName = tempnam ($UPLOAD_DIR, "diary");
		unlink($newName);
		link($DOWNLOAD_DIR.$fileList[0],$newName);
		$fileSize=filesize($newName);
		$returnFile[0]++;
		$returnFile[1]+=$fileSize;
		$linkName=basename($newName);
		// DB 기록	
		mysql_query("INSERT INTO $M_TABLE_FILE VALUES ('', '$fileList[0]', '$linkName', '$fileSize', '$nextID', '$date', '0')",$dbConnect);
	}
	if ($fileList[1])
	{
		$newName = tempnam ($UPLOAD_DIR, "diary");
		unlink($newName);
		link($DOWNLOAD_DIR.$fileList[1],$newName);
		$fileSize=filesize($newName);
		$returnFile[0]++;
		$returnFile[1]+=$fileSize;
		$linkName=basename($newName);
		// DB 기록	
		mysql_query("INSERT INTO $M_TABLE_FILE VALUES ('', '$fileList[1]', '$linkName', '$fileSize', '$nextID', '$date', '0')",$dbConnect);
	}
	return $returnFile;
}

$query="select * from $B_TABLE order by id ";
$result=mysql_query($query,$dbConnect);

$number=mysql_num_rows($result);
for($tempCount=0;$tempCount<$number;$tempCount++)
{
	$tempList=0;
	mysql_data_seek($result, $tempCount);
	$td=mysql_fetch_array($result);
	if($td[no] ==0) 
		continue;
//	echo "<font color='blue'>$td[id] &nbsp;&nbsp; $td[subject]</font><br>";

	// 다음 id 값
	$tempIdResult=mysql_query("SHOW TABLE STATUS like '$M_TABLE'",$dbConnect);
	$replyLocate=mysql_result($tempIdResult,0,'Auto_increment');
	$replyDepth='0';
	$replySortno='0';

	if($td[filename])
		$returnFile=FileChange($td[filename],$replyLocate,$td[date]);

	mysql_query("INSERT INTO $M_TABLE VALUES ('', '$td[subject]', '', '$td[name]', '$td[userid]', '$td[passwd]', '$td[email]', '$td[homepage]', '$td[passage]', '$returnFile[0]','$returnFile[1]', '$td[date]','$td[host]', '$td[count]', '$replyLocate', '$replyDepth', '$replySortno')",$dbConnect);

	PrintCommentForm($td,$td[userid],$replyLocate,$td[date]);

	if($td[son]) 
		PrintReply($td); // 답글이 있을 때 호출

}

?>