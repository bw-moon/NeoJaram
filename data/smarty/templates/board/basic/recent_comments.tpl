<img src="../images/icons/comments.gif"/>  <strong>{$title}</strong>
<ul id="recent_comment" style="margin-bottom:5px">
{foreach item=comment from=$comments}
<li><a href="./view.php?tableID={$comment.bid}&id={$comment.subid}#comment_{$comment.id}">{$comment.note|strip_tags|truncate:100}</a> by <a href="../jaram/memberinfo/?gid={$comment.usrid}">{$comment.user_name}</a>, Posted {$comment.time_gap|comment_time}, to <img src="../images/icons/page_white_text.gif" alt="article"/> <a href="./view.php?tableID={$comment.bid}&id={$comment.subid}">{$comment.title|strip_tags|truncate:80}</a>
</li>
{/foreach}
{if !$comments}
<li>새롭게 등록된 코멘트가 없네요.</li>	
{/if}
</ul>