<?
login_check();
dbconn();

if ($_GET['tableID'] && $_GET['id']) {
	$_GET['title'] = $_GET['title'].": ".$_GET['id'].": ".get_board_title($_GET['tableID'], $_GET['id']);
}

$query = "INSERT INTO jaram_bookmark (id, uid, bookmark_url, bookmark_title) VALUES ('', '".$_SESSION['jaram_user_uid']."', '".$_GET['bookmark_url']."', '".$_GET['title']."');";

mysql_query($query);
?>
Add bookmark for <b>'<?=urldecode($_GET['bookmark_url'])?>'</b> with title <b>'<?=urldecode($_GET['title'])?>'</b><br/><br/>

<a href="<?=$_GET['bookmark_url']?>">Visit the bookmarked page</a> - <a href="/">Back to your homepage</a>
