<?
// last modify by zeru at 2004. 5. 19
$url = 'http://192.168.1.91/wiki/wiki.php/RecentChanges?action=rss_rc';

?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
<tr>
    <td valign="top">
        <? show_rss_feed($url); ?><br/>
        <? //show_rss_feed($url);?><br/>
        <? //show_rss_feed('http://babjoe.jaram.org/wiki/wiki.php/RecentChanges?action=rss_rc');?>
		<? //show_rss_feed('http://zerosuni.jaram.org/wiki/wiki.php/RecentChanges?action=rss_rc');?>
        <? //show_rss_feed('http://qubee.jaram.org/moniwiki/wiki.php/RecentChanges?action=rss_rc');?>
    </td>
    <td valign="top">
        <? //show_rss_feed($url);?><br/>
        <? //show_rss_feed($url);?><br/>
        <? //show_rss_feed($url); ?>
        <? //show_rss_feed('http://tasy.jaram.org/wiki/wiki.php/RecentChanges?action=rss_rc');?><br/>
        <? //show_rss_feed('http://womin.net/tikiwiki/tiki-wiki_rss.php?ver=2');?>
    </td>
        <? //show_rss_feed('http://likimda.jaram.org/wiki.php/RecentChanges?action=rss_rc');?>
</tr>
</table>