{* smarty *}
{* 게시판 header *}
<div id="boardList">
{if $draftArticles}
<h4><img src="../images/icons/page_edit.gif"/> 작성중이던 글이 있습니다</h4>
<ul>
{foreach item=article from=$draftArticles}
<li><a href="./write.php?{query_string draft_id=$article.id}">{$article.title}</a> - 최종 수정일 : <small>{$article.date}</small>, <a href="./delete_note.php?{query_string action="delete_draft_article" draft_id=$article.id}">이 글을 삭제</a></li>
{/foreach}
</ul>
{/if}
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="board">
<thead>
<tr class="boardHeader">
	<th width="8%">No</th>
	<th>Subject</th>
	<th width="50">Name</th>
	<th width="30"><a href="./bbs.php?{query_string sort="count" asc=$asc}">View</a></th>
	<th width="60"><a href="./bbs.php?{query_string sort="date" asc=$asc}">Date</a></th>
</tr>
</thead>
<tbody>
{* 페이지가 지정된 위치에 벗어나 거나 검색에 대한 게시물이 없거나 빈 게시판일 경우 *}
{if $listData==''}
<tr>
	<td colspan="5" align="center">게시물이 없습니다.</td>
</tr>	
{else}

{* 게시판 리스트 본문 *}
{foreach item=tdData from=$listData}
<tr>
	<td align="center" class="small">
{if $tdData.depth<0}
	<img src="../images/icons/bell.gif" alt="notice" title="{$articleStartNum--}"/>	
{elseif $tdData.id eq $smarty.get.id}
	<img src="../images/icons/bullet_go.gif" alt="{$articleStartNum}" title="{$articleStartNum--}"/>
{else}
	{$articleStartNum--}
{/if}
	</td>
	<td>
{* 답글 구분 공백 *}
{section name=space loop=$tdData.depth}
&nbsp;&nbsp;&nbsp;
{/section}
{if $tdData.depth<0}
	<b><a href="./view.php?{query_string id=$tdData.id pageID=$pageID}">{$tdData.title}</a></b>
{else}
	<a href="./view.php?{query_string id=$tdData.id pageID=$pageID}">{$tdData.title}</a>
{/if}
	
{* 코멘트 갯수 *}
<span class="sub">[{$tdData.comment_count}]</span>

{* 오늘 올라온 글 표시 *}
{if $smarty.now|date_format:"%Y%m%d" == $tdData.date|date_format:"%Y%m%d"}
<img src="../images/icons/new.gif" alt="new" title="new"/>
{/if}

	</td>
	<td align="center">
{* User의 글은 링크된다. 혹은 특유 구분한다. *}
{if $tdData.usrid!='' && $tdData.usrid!='1500'}
	<a href="../jaram/memberinfo/?gid={$tdData.usrid}">{$tdData.name}</a>
{else}
{$tdData.name}
{/if}
	</td>
{* 카운터 디자인 수정시 PU(Pare coUnter) 밖으로 수정할것 *}
	<td align="center" class="small"><!--PU="{$tdData->id}"-->{$tdData.count}<!--PU--></td>
{* 날짜 양식은 년-월-일, Title로 세부히 *}
	<td align="center"><span title="{$tdData.date|date_format:"%Y년, %m월(%B), %A, %e일\n%r"}" class="small">{$tdData.date|date_format:"%y-%m-%d"}</span></td>
</tr>
{/foreach}

{/if}
</tbody>
</table>
<div id="boardTool">
<a href="../?page=monitor_add&amp;tableID={$tableID}">Monitor This Board</a> | <a href="write.php?tableID={$tableID}" title="글작성">Write</a>
</div>

<div id="pager">
{$pager_links.all}
</div>
{* 검색 부분 *}
<div id="boardSearch">
<form name="search" id="boardSearchForm" method="get" action="./bbs.php">
<input type="hidden" name="tableID" value="{$tableID}"/>
{html_checkboxes name="search_type" options=$search_types selected=$search_type  separator=" "} 
<input type="text" name="search_value" id="search_value" size="10" value="{$search_value}"/>
<input type="submit" value="Search"/>
</form>
</div>
</div>