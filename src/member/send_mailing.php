<?

// 그룹 select form
$whole_group = array_merge(get_group_array(1000, 1300), get_group_array(3000,5000));
$group_select = get_select($whole_group, "gid[]", "gid", "group_name", "nothing", "size=\"6\" multiple=\"multiple\"  class=\"inputtext\"");

function get_user_info($uid) {
    $result = mysql_query("SELECT * FROM jaram_users WHERE uid='".$uid."';");
    $rows = mysql_fetch_array($result);
    return $rows;
}

if ($_POST['action'] == "send") {
    $from = get_user_info($_SESSION['jaram_user_uid']);
    $content['from_name'] = $_SESSION['jaram_user_name'];
    $content['from_address'] = $from['user_email'];
    $content['subject'] = $_POST['subject'];
    $content['message'] = auto_link(nl2br($_POST['body']));
    $content['message'] .= "<br/><br/><br/>-------------------------------------------------<br/>";
    $content['message'] .= "한양대학교 전산전공학회 자람에서 발송하는 단체메일입니다.<br/>";
    $content['message'] .= "자람 <a href=\"http://jaram.org\" target=\"_blank\">http://jaram.org</a><br/><br/>";

    $fault_list = array();
    $count = 0;

    foreach($_POST['gid'] as $gid) {
        $sql = "SELECT b.user_email, b.user_name FROM jaram_user_group AS a LEFT JOIN jaram_users AS b ON a.uid=b.uid WHERE a.gid='".$gid."';";
        $result = mysql_query($sql);
        while ($rows = mysql_fetch_array($result)) {
            $content['to_name'] = $rows['user_name'];
            $content['to_address'] = $rows['user_email'];
            $count++;
            if (!SEND_HTML_MAIL($content)) {
                $fault_list[] = $gid;
            }
        }

    }
    if (count($fault_list) == 0) {
        show_std_msg($count."개의 메일이 전송되었습니다.\n\n2초후 자동으로 이전 페이지로 이동합니다.", "std", "/?page=mailing");
    } else {
        $msg = "총 ".$count."개의 메일 중 ".count($fault_list)."개의 메일 전송이 실패하였습니다.\n\n";
        $msg .= "메일 전송에 실패한 유저는 아래와 같습니다.\n";
        foreach ($fault_list as $gid) {
            $usr = get_user_info($gid);
            $msg .= " - ".$usr['user_name']."(".$usr['user_email'].")\n";
        }
        show_error_msg($msg);
    }


} else {
?>
그룹 전체의 구성원에게 메일을 보낼 수 있습니다. html이 사용가능하며 첨부파일 기능은 일단 없습니다.<br/>
필요하신 분은 서버에 파일을 올리고 링크를 하는 방법을 택하시길 바랍니다.
.<br/><br/>
<form action="/?page=mailing" method="post">
<input type="hidden" name="action" value="send"/>
<b>From:</b> <?=$_SESSION['jaram_user_name']?>(<b><?=$_SESSION['jaram_user_id']?></b>)
<br/>
<b>To:</b> &nbsp;&nbsp;&nbsp;&nbsp;<?=$group_select?>&nbsp;&nbsp; <span style="color:red">*</span> 중복선택이 가능합니다. ctrl, shift이용
<br/>
<br/>

<b>Subject:</b><br/>
<input type="text" name="subject" size="50" maxlength="60" value="" class="inputtext"/><br/><br/>

<b>Message:</b><br/>
<TEXTAREA NAME="body" ROWS="15" COLS="60" WRAP="HARD" class="inputtext"></TEXTAREA>
<br/><br/>
<CENTER>
<INPUT TYPE="SUBMIT" NAME="send_mail" VALUE="Send Message" class="text">
</CENTER>
</form>
<? } ?>