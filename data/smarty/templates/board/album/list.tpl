{* smarty *}
{* 게시판 header *}
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="board">
<thead class="boardHeader">
<tr>
	<th width="8%"><a href="./bbs.php?tableID={$tableID}&amp;startPage={$nowPage}&amp;sortData={$printValueSortNo}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;search={$search}">No</a></th>
	<th width="60">Category</th>
	<th width="80" >Photograph</th>
	<th>Subject</th>
	<th width="50">Name</th>
	<th width="30"><a href="./bbs.php?tableID={$tableID}&amp;startPage={$nowPage}&amp;sortData={$printValueSortCount}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;search={$search}">View</a></th>
	<th width="60"><a href="./bbs.php?tableID={$tableID}&amp;startPage={$nowPage}&amp;sortData=cmt&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;search={$search}">Date</a></th>
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
	Notice
{else}
	{$tdData.id}
{/if}
	</td>
	<td width="60" align="center">
	{$tdData.category_name}
	</td>
	<td width="80" align="center">
	<a href="javascript:window.open('thumbnail.php?tableID={$tableID}&amp;id={$tdData.id}','','resizable=yes, scrollbars=yes, width=400, height=200');"><img src="imgview.php?tableID={$tableID}&amp;fileid={$tdData.file_id}&amp;resize=true" alt="썸네일보기" border="0"/></a>
	</td>
	<td>
{* 답글 구분 공백 *}
{section name=space loop=$tdData.depth}
&nbsp;&nbsp;&nbsp;
{/section}
{if $tdData.depth<0}
	<b><a href="view.php?tableID={$tableID}&amp;id={$tdData.id}&amp;startPage={$smarty.get.pageID}&amp;sortData={$nowSortData}&amp;searchcrc={$searchcrc}">{$tdData.title}</a></b>
{else}
	<a href="view.php?tableID={$tableID}&amp;id={$tdData.id}&amp;startPage={$smarty.get.pageID}&amp;sortData={$nowSortData}&amp;searchcrc={$searchcrc}">{$tdData.title}</a>
{/if}
	
{* 코멘트 갯수 *}
{* 코멘트 디자인 수정시 PM(Pare coMment) 밖으로 수정할것 *}
{if $tdData.subNum != 0}
	<span class="small">[<!--PM="{$tdData.id}"-->{$tdData.subNum}<!--PM-->]</span>
{else}
	<span class="small">[<!--PM="{$tdData.id}"-->0<!--PM-->]</span>
{/if}

{* 오늘 올라온 글 표시 *}
{if $smarty.now|date_format:"%Y%m%d" == $tdData.date|date_format:"%Y%m%d"}
<img src="../images/icons/new.gif" alt="new" title="new"/>
{/if}

	</td>
	<td align="center">
{* User의 글은 링크된다. 혹은 특유 구분한다. *}
{if $tdData.usrid!='' && $tdData.usrid!='1500'}
	<a href="../jaram/memberinfo/?gid={$tdData.usrid}">{$tdData.user_name}</a>
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
<tbody>
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
<label for="search">검색어</label> <input type="text" name="search" id="search" size="10" class="input" value="{$smarty.get.search}"/>
<input type="submit" value="Search"/>
</form>
</div>
