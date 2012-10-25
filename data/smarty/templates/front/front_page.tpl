<div id="grid_system">
{msg_form type=$msg_type msg=$msg}

<div >
<h3><img src="./images/icons/asterisk_orange.gif"/> Recent Notice</h3>
{foreach from=$notice item=row name=notice}
	{if $smarty.foreach.notice.first}
		<h4 class="big"><a href="./board/view.php?tableID=notice&amp;id={$row.id}" class="Notice">{$row.title}</a></h4>
		<p>
		{$row.note}
		</p>
		<span class="sub"><strong>Posted on</strong> {$row.date}</span>
	{else}
		<h4><img src="./images/icons/note_go.gif" alt="다음 공지사항 : "/> <span class="sub">{$row.date}</span> <a href="./board/view.php?tableID=notice&amp;id={$row.id}" class="Notice">{$row.title}</a> </h4>
	{/if}
{/foreach}

</div>

<!-- 최신북리뷰 -->
<div >
{$book_info}
</div>

<!-- 세미나 -->
<div >
{$seminar_info}
</div>

<!-- 질문/답변 -->
<div >
<h3><img src="./images/icons/user_comment.gif"/> Question &amp; Answer</h3>
{$qna}
</div>


<!-- 뉴스/팁 -->
<div >
<h3><img src="./images/icons/newspaper.gif"/> News&amp;Tips</h3>
{$news}
</div>


<!-- rss 피드 -->
<div >
<h3><img src="./images/icons/note.gif"/> {$rss->channel.title}</h3>
<ul>
{foreach from=$rss->items item=item}
	<li>
	{if $item.wiki.status eq "updated"}
		{assign var="status" value="<img src=\"./images/moni-updated.gif\" alt=\"updated\"/>"}
	{elseif $item.wiki.status eq "new"}
		{assign var="status" value="<img src=\"./images/moni-new.gif\" alt=\"new\"/>"}
	{/if}
	{$status} <a href=\"{$item.link}\" target=\"_blank\">{$item.title}</a>
	</li>
{/foreach}
</ul>
</div>
</div>