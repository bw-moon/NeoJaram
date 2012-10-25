<?
require_once realpath(dirname(__FILE__).'/../../../lib/includes')."/header.inc.php";
require_once LIBRARY_PATH.'/magpierss-0.5.2/rss_fetch.inc';

//show(get_auth($PROGRAM_INFO));

function show_rss_feed ($url) {
    $rss = fetch_rss($url);

    echo "<table withd=\"100%\" cellpadding=\"1\" cellspacing=\"0\" border=\"0\">";
    echo "<tr><td colspan=\"2\">";
    echo "<a href=\"",$rss->channel['link'],"\" class=\"a12b\" target=\"_blank\">", $rss->channel['title'], "</a><br/>\n";
    echo "</td></tr>";

    $tempRssItems=$rss->items;

    if($tempRssItems)
        foreach ($tempRssItems as $item ) {
            $title = $item[title];
            $url   = $item[link];

            //print_r($item);

            $diff_h = "<a href=\"".$item['wiki']['diff']."\">";
            $diff_t = "</a>";

            if ($item[wiki][status] == "updated")
                $status = $diff_h."<img src=\"".WEB_ABS_PATH."/images/moni-updated.gif\" border=\"0\" alt=\"\"/>".$diff_t;
            else if ($item[wiki][status] == "new")
                $status = "<img src=\"".WEB_ABS_PATH."/images/moni-new.gif\" alt=\"\"/>";
            else if ($item[wiki][status] == "deleted")
                $status = "<img src=\"".WEB_ABS_PATH."/images/moni-deleted.gif\" alt=\"\"/>";
            else 
                $status = "";

            echo "<tr><td align=\"center\" class=\"text\">$status</td>";
            echo "<td class=\"text\"><a href=$url>$title</a><br/></td></tr>\n";
        }
    echo "</table>";
}

?>
자람 위키 중계소입니다.<br/>
등록을 윈하시는 분들은 베너(가능하면)와 rss나 rdf파일의 주소를 쪽지로 <a href="http://www.jaram.org/jaram/memberinfo/?gid=1010">웹팀</a>에게 보내주시기 바랍니다.<br/>
(웹팀 리스트를 잘 살펴보시고 재학중이거나 연락이 잘 될것 같은 이에게 보내주세요)<br/>
<br/>
속도향상을 위해서 RSS문서들을 수합하는 주기는 3시간으로 되어 있습니다. 그러므로 올라온 글이라 하더라도 아직 목록에 없을 수 있으니 유념하시기 바랍니다.<br/>
<br/>

<?

$start = microtime();
require_once (HOMEPAGE_PATH."/studyzone/jaramwiki/contents.inc.php");
$t = get_microtime($start, microtime());

echo("<br/>elapsed time $t seconds");
?>
<?
include INCLUDE_PATH."/footer.inc.php";
?>
