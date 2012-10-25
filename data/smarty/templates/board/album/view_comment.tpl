{foreach item=tdData from=$comments}
<dt>
{if $tdData.user_having_image2 eq 'true'}
<span class="link" onClick="javascript:window.open('image_view.php?uid2={$tdData.usrid}','','resizable=yes, scrollbars=no, width=screen.availWidth, height=screen.availHeight');"><img src="../member/profile/{$tdData.usrid}_sub_resize" border="0"></span>
{/if}
<strong>
{* User와 비유저의 구분 혹은 User 정보 링크 *}
{if $tdData.usrid ne '' && $tdData.usrid ne '1500'}
<a href="../jaram/memberinfo/?gid={$tdData.usrid}">{$tdData.user_name}</a>
{else}
{$tdData.name}
{/if}
says</strong>, 
{$tdData.date|date_format:"%Y년 %m월 %d일 %H:%M"}{if $tdData.usrid eq $smarty.session.jaram_user_uid}, <a href="./delete_comment.php?tableID={$tableID}&amp;superid={$id}&amp;id={$tdData.id}&amp;startPage={$startPage}" title="코멘트 삭제하기">Delete</a>
{/if}
</dt>
<dd>
{$tdData.note|replace:"<S":"&lt;S"|replace:"<s":"&lt;s"|nl2br}
</dd>
{/foreach}