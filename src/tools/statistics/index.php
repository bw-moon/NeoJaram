<?
/**********************************
 *  Author : lovelyk2, Hanyang Univ. Ansan EECS, Jaram
 *  Start Date : 2006-5-24
 *  End Date : 2006-5-24
 *********************************/
include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/library.inc.php");


    ///////////////////////////////////////
    //  자람 사람이 아니면 볼 수 없다.
    if( !$_SESSION['jaram_user_id'])
    {
?>
<?
    }
    //
    ///////////////////////////////////////

  // include_once(realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php");
    dbconn();

    $query = "select * from jaram_user_group where uid=".$_SESSION['jaram_user_uid']." && gid=1001";    // 1001은 회장단 gid 임.
    $result = mysql_query($query);
    $count = mysql_num_rows($result);

    if( $count <= 0 && $_SESSION['jaram_user_id']!="lovelyk2")
    {
?>

<?
    }

    /////////////////////////////////////////////
    //  jaram_page_load_time 테이블의 min, max 값을 읽어온다.
    //
    $query = "select min(timestemp), max(timestemp) from jaram_page_load_time";

    $first_time = time();
    $result = mysql_query($query);
    $result_time = time() - $first_time;

    $min_time = mysql_result($result, 0, 0);
    $max_time = mysql_result($result, 0, 1);

    $min_time = date("Y년 m월 d일", $min_time);
    $max_time = date("Y년 m월 d일", $max_time);
    //
    ////////////////////////////////////////////


    /////////////////////////////////////////////
    //  op로 읽어온 날짜를 계산한다.
    //
    $op = $_GET['op'];

    if( $op != "" )
    {
        $time = time();
        $t_year = date("Y", $time);
        $t_month = date("m", $time);
        $t_day = date("d", $time);

        if( $op == "lastweek" )
        {
            $f_time = mktime(0, 0, 0, $t_month, intval($t_day)-6, $t_year);   // one week
        }
        else if( $op == "thisweek" )
        {
            $t_day_w = date("w", $time);

            $f_time = mktime(0, 0, 0, $t_month, intval($t_day)-intval($t_day_w), $t_year);   // this week
        }
        else if( $op == "lastmonth" )
        {
            $f_time = mktime(0, 0, 0, $t_month, $t_day-29, $t_year);   // last month
        }
        else if( $op == "thismonth" )
        {
            $f_time = mktime(0, 0, 0, $t_month, 1, $t_year);   // this month
        }
        else if( $op == "thisyear" )
        {
            $f_time = mktime(0, 0, 0, 1, 1, $t_year);
        }

        $f_year = date("Y", $f_time);
        $f_month = date("m", $f_time);
        $f_day = date("d", $f_time);
    }
    //
    ////////////////////////////////////////////

    /////////////////////////////////////////////
    //  POST로 넘어온 값을 textbox 에 들어갈 변수에 대입시킨다.
    //
    if( $_POST['f_year'] )
    {
        $f_year = $_POST['f_year'];
        $f_month = $_POST['f_month'];
        $f_day = $_POST['f_day'];

        $t_year = $_POST['t_year'];
        $t_month = $_POST['t_month'];
        $t_day = $_POST['t_day'];

        $search = $_POST['search'];
    //
    ////////////////////////////////////////////

    /////////////////////////////////////////////
    //  지정된 날짜의 값을 계산한다.
    //
        $f_time = mktime( 0, 0, 0, $_POST['f_month'], $_POST['f_day'], $_POST['f_year']);
        $t_time = mktime( 23, 59, 59, $_POST['t_month'], $_POST['t_day'], $_POST['t_year']);

        $query = "select u.user_name, u.user_id, count(p.uid) as count from jaram_page_load_time as p left join jaram_users as u using(uid) where timestemp >= " . $f_time  . " && timestemp <= " . $t_time . " group by p.uid order by count desc";
    }
    //
    ////////////////////////////////////////////
?>
<!-- jaram_statics by lovelyk2 -->
<h2>jaram statistics</h2>
access log : <b><?=$min_time?></b>부터 <b><?=$max_time?></b>까지의 로그가 남아있습니다.</b><br />

<a href="index.php?op=lastweek">최근 1주일</a> | <a href="index.php?op=thisweek">이번주</a> | <a href="index.php?op=lastmonth">최근 30일</a> | <a href="index.php?op=thismonth">이번달</a> | <a href="index.php?op=thisyear">올해</a><br />

<form name="stat" action="index.php" method="post">
    <input type="text" name="f_year" size="4" maxlength="4" value="<?=$f_year?>"/>년 <input type="text" name="f_month" size="2" maxlength="2" value="<?=$f_month?>"/>월 <input type="text" name="f_day" size="2" maxlength="2" value="<?=$f_day?>"/>일 ~ <input type="text" name="t_year" size="4" maxlength="4" value="<?=$t_year?>"/>년 <input type="text" name="t_month" size="2" maxlength="2" value="<?=$t_month?>"/>월 <input type="text" name="t_day" size="2" maxlength="2" value="<?=$t_day?>"/>일<br />
    <input type="text" name="search" size="15" value="<?=$search?>"/>
<input type="button" value="Submit" onclick="document.stat.submit();" />
</form>

<?
    if( $_POST['f_year'] )
    {
        $delay = time();
        $result = mysql_query($query);
        $delay = time() - $delay;

        $row_count = mysql_num_rows($result);

        $min_time = $f_year."년 ".$f_month."월 ".$f_day."일";
        $max_time = $t_year."년 ".$t_month."월 ".$t_day."일";
?>
<h4><?=$min_time?>부터 <?=$max_time?>까지의 통계입니다.</h4>
<?=$row_count?> rows selected(<?=$delay?> sec)<br />
<?
    flush();flush();
?>
<table cellspacing="0" cellpadding="10" border="1">
    <tr><td>no</td><td>이름(id)</td><td>count</td></tr>
<?
        $i = 1;
        while($fetch = mysql_fetch_array($result))
        {
            $name = ($fetch[0] != $_POST['search'])?$fetch[0]:"<font color=\"red\">".$fetch[0]."</font>";
            $id = ($fetch[1] != $_POST['search'])?$fetch[1]:"<font color=\"red\">".$fetch[1]."</font>";
            $count = ($fetch[2] != $_POST['search'])?$fetch[2]:"<font color=\"red\">".$fetch[2]."</font>";
?>
    <tr><td><?=$i?></td><td><?=$name?>(<?=$id?>)</td><td><?=$count?></td></tr>
<?
            $i++;
        }
?>
</table>
<?
    }

//include_once INCLUDE_PATH."/footer.inc.php";
?>

