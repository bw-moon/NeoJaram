<?
/*
 * Memberzone 메뉴 메인
 */
include_once(realpath('../../lib/includes')."/header.inc.php");

$DIARY = recent_article_list("diary", 4);
$CLASSINFO = recent_article_list("classinfo", 4);
$DISCUSSION = recent_article_list("discussion", 4);
$GRADUATED = recent_article_list("graduated", 4);
$ALBUM = recent_article_list("album", 4);
$FRESHMAN = recent_article_list("freshmanboard", 4);

?>
<div id="grid_system">
<div class="halfSize floatLeft">
<h3>Diary</h3>
<?=show_article_list($DIARY, "diary");?>
</div>
<div class="halfSize floatRight">
<h3>Discussion</h3>
<?=show_article_list($DISCUSSION, "discussion");?>
</div>
<div class="halfSize floatLeft">
<h3>Class Infomation</h3>
<?=show_article_list($CLASSINFO, "classinfo");?> 
</div>
<div class="halfSize floatRight">
<h3>Jaram Album</h3>
<?=show_article_list($ALBUM, "album");?> 
</div>
<div class="halfSize floatLeft">
<h3>Graduated Board</h3>
<?=show_article_list($GRADUATED, "graduated");?> 
</div>
<div class="halfSize floatRight">
<h3>Freshman Board</h3>
<?=show_article_list($FRESHMAN, "freshmanboard");?> 
</div>
</div>
<?
include_once INCLUDE_PATH."/footer.inc.php";
?>