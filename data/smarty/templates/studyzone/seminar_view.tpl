<div id="seminar_paper">
<h1>{$data.seminar_topic} <small>: {$data.schedule_start|to_timestamp|date_format:"%Y년 %m월 %d일 "} <a href="./seminar_edit.php?seminar_id={$data.seminar_id}" class="small">edit</a>, <a href="./?year={$data.schedule_start|to_timestamp|date_format:"%Y"}" class="small">list</a></small></h1>

<p align="right">{"sound"|icon} 발표자 : 
{if $data.seminar_group_id}
	<a href="{$context_root}/jaram/memberinfo/?gid={$data.seminar_group_id}">{$data.seminar_group_name}</a>
{else}
	{$data.seminar_group_name}
{/if}
</p>
<p align="right">{$uploadfile}</p>

<h2>{"report"|icon} 개요</h2>
<p>
{$data.seminar_desc|auto_link|nl2br}
</p>

<h2>{"folder_page"|icon} 세미나에 포함되어 있는 주제들</h2>
<div id="plane_view">
{if $data.seminar_topics_type eq 'wakka'}
	{$data.seminar_topics|wakka_wiki}
{else}
	{$data.seminar_topics}
{/if}
</div>

<h2>{"lightbulb"|icon} 얻을 수 있는 이득</h2>
<p>
{$data.seminar_benefit|nl2br}
</p>
</div>

<p>
{"report_user"|icon} {$article_readers}
</p>

<div id="seminar_comments">
{foreach from=$comments item=comment}
	<div id="seminar_comments_{$comment.comment_id}" class="seminar_comment">
		{$comment.text|nl2br|auto_link} 
		<small>by</small> <a href="../../jaram/memberinfo/?gid={$comment.uid}"><strong>{$comment.user_name}</strong></a>
	</div>
{/foreach}
</div>

{if $smarty.session.jaram_user_uid}
<form name="seminar_comment" method="post" action="./post.php" style="margin: 0;">
<input type="hidden" name="seminarID" value="{$data.seminar_id}"/>
<input type="hidden" name="mode" value="comment"/>
<table width="100%" cellspacing="0" cellpadding="3" border="0" class="text">
	<tr>
		<td width="35" align="right"><b>text</b></td>
		<td><textarea name="text" cols="35" rows="6" style="width:100%;height:100px"></textarea></td>
		<td width="100"><input type="submit" value="입력하기" style="width:100%;height:100px"/></td>
    </tr>
</table>
</form>
{/if}
<br/>