<?
/*************************
 *	Author : lovelyk2. Hanyang Univ. Ansan, EECS
 *	Start Date : 2003-05-21
 * Last Update : 2003-05-21
 *************************/
 include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");

 dbconn();
    function printError()
    {
        echo "<script language=\"javascript\" type=\"text/javascript\">\n";
        echo "\talert('잘못된 param 입니다. 창을 닫습니다.');\n";
        echo "\twindow.close();\n";
        echo "</script>\n";
        exit();
    }

    // 읽은 적이 없고 지워지지 않았은 메세지를 receive 가 읽을때
    if( $_SESSION["jaram_user_uid"] )
    {
        if( $_GET['type'] == "receive" )
        {
            $query = "update jaram_web_messenger set is_read = 'Y', read_time = '" . time() . "' where id = ".$_GET['id']." and receive = " . $_SESSION["jaram_user_uid"] . " and ( is_read = 'N'  or is_read = 'F' ) and delete_R = 'N' ";

            $status = @mysql_query($query);
            if( !$status )
            {
                printError();
            }
        }
        if( $_GET['type'] == "receive" )
            $query = "select m1.send_time, m1.send, m2.message from jaram_web_messenger as m1 left join jaram_web_message as m2 on m1.message = m2.id where m1.id = ".$_GET['id'] . " and m1.receive = " . $_SESSION["jaram_user_uid"] . " and delete_R = 'N' ";
        else if( $_GET['type'] == "send" )
            $query = "select m1.send_time, m2.message from jaram_web_messenger as m1 left join jaram_web_message as m2 on m1.message = m2.id where m1.id = ".$_GET['id'] . " and m1.send = " . $_SESSION["jaram_user_uid"] . " and delete_S = 'N' ";
        else
        {
            echo "2";
            //printError();
        }

        $result = mysql_query($query);
        $field = mysql_fetch_array($result);

        if( mysql_num_rows($result) < 1 )
        {
            echo "3";
            //printError();
        }

        $send_time = "at " . date("Y/m/d H시i분", $field['send_time']);
        $sender = $field['send'];

echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?".">"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jaram 3rd Renewal : 2003</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="Content-Language" content="ko-KR" />
<meta name="Generator" content="EditPlus" />
<meta name="Author" content="Neo Jaram Makers" />
<meta name="Description" content="한양대학교 전자컴퓨터공학부 전산전공학회 자람입니다." />
<!-- 공통 스타일 시트 -->
<link rel="stylesheet" type="text/css" href="<?=WEB_ABS_PATH?>/css/main.css" />

<script language="javascript" type="text/javascript">
function refresh()
{
    opener.window.location.reload();
    window.close();
}
function reply(uid)
{
    window.resizeTo(300,400);
    window.location="message_write_form.php?uid="+uid;
}
function back()
{
    history.back();
}
</script>
</head>
<body bgcolor="#f1f1f1" link="#1166bb" vlink="#666666" alink="#ddeeff">
<table width="100%" cellpadding="0" cellspacing="5" border="0" class="text">
    <tr>
        <td><div class="SubMenu">Jaram Messenger</div></td>
    </tr>
    <tr>
        <td class="text">
        <b>Comment</b><br/>
            <?=nl2br($field['message'])?>
            <br/><br/>
            <div align="right"><?=$send_time?></div>
        </td>
    </tr>		
    <tr valign="center">
        <td align="right">
<?
    if( $_GET['from'] == "sendlist" )
    {
?>
            <a href="javascript:back()"><img src="<?=WEB_ABS_PATH?>/images/button/btn_back.gif" border="0" alt="back"/></a>&nbsp;
<?
    }

    else if( $_GET['from'] == "list" )
    {
?>
            <a href="javascript:reply(<?=$sender?>)"><img src="<?=WEB_ABS_PATH?>/images/button/btn_reply.gif" border="0" alt="reply"/></a>
<?
    }
?>
            <a href="javascript:refresh()"><img src="<?=WEB_ABS_PATH?>/images/button/btn_check.gif" border="0" alt="check"/></a>
            <!-- Reply -->
            <!-- Delete -->
        </td>
    </tr>
</table>
</body>
</html>

<?
    }
    else
        {
            echo "4";
            printError();
        }


?>
