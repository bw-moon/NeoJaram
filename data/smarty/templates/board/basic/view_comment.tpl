{foreach item=comment from=$comments}
<dt id="comment_dt_{$comment.id}">
{if $comment.user_having_image2 eq 'true'}
<span class="link" onClick="javascript:window.open('image_view.php?uid2={$comment.usrid}','','resizable=yes, scrollbars=no, width=screen.availWidth, height=screen.availHeight');"><img src="{$imageManager->getCommentImg($comment.usrid)}" border="0"></span>
{/if}
<strong>
{* User와 비유저의 구분 혹은 User 정보 링크 *}

{if $comment.usrid ne '' && $comment.usrid ne '1500'}
<a href="../jaram/memberinfo/?gid={$comment.usrid}" name="comment_{$comment.id}">{$comment.user_name}</a>
{else}
<a  name="comment_{$comment.id}">{$comment.name}</a> 
{/if}
says</strong>, 
<span class="sub" title="{$comment.date}">Posted {$comment.time_gap|comment_time}</span> {if $comment.usrid eq $smarty.session.jaram_user_uid}, <a href="javascript:deleteComment('{$comment.id}')" title="코멘트 삭제하기" class="sub">Delete</a>
{/if}
</dt>
<dd id="comment_dd_{$comment.id}">
{$comment.note|nl2br}
</dd>
{/foreach}