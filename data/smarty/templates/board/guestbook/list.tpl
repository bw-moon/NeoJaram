{* smarty *}
{include_php file="bbs_script.html"}
{* 게시판 header *}
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="text">
<tr style="background-color: #555555">
	<td align="center" width="8%" height="25"><a href="bbs.php?tableID={$tableID}&amp;startPage={$nowPage}&amp;sortData={$printValueSortNo}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;search={$search}" class="bbshead">No</a></td>
	<td align="center" class="bbshead">Subject</td>
	<td align="center" width="50" class="bbshead">Name</td>
	<td align="center" width="55" class="bbshead">Date</td>
</tr>
<tr>
	<td colspan="5" height="2" bgcolor="#999999"></td>
</tr>
<tr>
	<td colspan="5" height="1" bgcolor="#eeeeee"></td>
</tr>
{* 페이지가 지정된 위치에 벗어나 거나 검색에 대한 게시물이 없거나 빈 게시판일 경우 *}
{if $listData==''}
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
<tr>
	<td height="1" colspan="7" align="center">게시물이 없습니다.</td>
</tr>	
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
{else}

{* 게시판 리스트 본문 *}
{foreach item=tdData from=$listData}
<tr>
	<td align="center">
{if $tdData->depth==-1}
	Notice
{else}
	{$tdData->id}
{/if}
	</td>
	<td>
{* 답글 구분 공백 *}
{section name=space loop=$tdData->depth}
&nbsp;&nbsp;&nbsp;
{/section}
{if $tdData->depth==-1}
	<b><a href="view.php?tableID={$tableID}&amp;id={$tdData->id}&amp;startPage={$nowPage}&amp;sortData={$nowSortData}&amp;searchcrc={$searchcrc}">{$tdData->title}</a></b>
{else}
	<a href="view.php?tableID={$tableID}&amp;id={$tdData->id}&amp;startPage={$nowPage}&amp;sortData={$nowSortData}&amp;searchcrc={$searchcrc}">{$tdData->title}</a>
{/if}
	</td>
	<td align="center">
{* User의 글은 링크된다. 혹은 특유 구분한다. *}
{if $tdData->usrid!='' && $tdData->usrid!='1500'}
	<a href="/jaram/memberinfo/?gid={$tdData->usrid}">{$tdData->user_name}</a>
{else}
{$tdData->name}
{/if}
	</td>
{* 날짜 양식은 년-월-일, Title로 세부히 *}
	<td align="center"><font title="{$tdData->date|date_format:"%Y년, %m월(%B), %A, %e일\n%r"}">{$tdData->date|date_format:"%y-%m-%d"}</font></td>
</tr>
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
{/foreach}

{/if}
</table>
<img src="/images/t.gif" width="1" height="10" alt="" /><br />

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
<tr>
	<td height="5" colspan="2"></td>
</tr>
<tr>
	<td width="90%">
{* 게시물이 없는 위와 같은 경우 페이징을 보일필요 없다. *}
{if $listData!=''}
{* 페이지 이동 부분  *}
{if $preViewStart != ""}
<a href="bbs.php?tableID={$tableID}&amp;startPage={$preViewStart}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}">[prev]</a>&nbsp;
{/if}
{foreach item=pagePrint from=$pagePrintArray}
{if $nowPage == $pagePrint}
&nbsp;<span class="a12b">{$pagePrint}</span>&nbsp;
{else}
&nbsp;<a href="bbs.php?tableID={$tableID}&amp;startPage={$pagePrint}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}">{$pagePrint}</a>&nbsp;
{/if}
{/foreach}
{if $nextViewStart != ""}
<a href="bbs.php?tableID={$tableID}&amp;startPage={$nextViewStart}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}">[next]</a>&nbsp;
{/if}
{/if}
	</td>
	<td width="50%" align="right"><b><a href="write.php?tableID={$tableID}">Write</a></b></td>
</tr>
</table>

{* 검색 부분 *}
<form name="search" method="post" action="bbs.php?tableID={$tableID}">
<table border="0" width="100%" cellpadding="0" cellspacing="0" class="text" align="center">
<tr>
	<td><a href="/?page=monitor_add&amp;tableID={$tableID}">Monitor This Board</a></td>
	<td align="right">
		<table border="0" cellpadding="3" cellspacing="0" class="text">
		<tr><td>
		이름<input type="checkbox" name="sname" />
		제목<input type="checkbox" name="ssubject" />
		본문<input type="checkbox" name="snote" />
		</td>
		<td>검색어&nbsp;<input type="text" name="search" size="10" class="inputtext" style="height:20px;" /></td>
		<td><input type="image" name="mode" value="Search" src="/images/button/btn_search.gif" /></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>