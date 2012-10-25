{* smarty *}
{include_php file="bbs_script.html"}
{* 게시판 header *}
{* 페이지가 지정된 위치에 벗어나 거나 검색에 대한 게시물이 없거나 빈 게시판일 경우 *}
{if $listData==''}
<table width="100%" cellspacing="0" border="0" align="center" valign="top">
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
<tr>
	<td height="1" colspan="7" align="center" class="text">게시물이 없습니다.</td>
</tr>	
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
</table>
{else}
{* 검색 부분 *}
<form name="search" method="post" action="bbs.php?tableID={$tableID}">
<table border="0" cellpadding="3" cellspacing="0" class="text" align="left">
<tr>
	<td>
	<select name="kind" class="inputtext" class="inputtext">
		<option value="">전체</option>
{foreach item=optionData from=$categoryData}
		<option value="{$optionData->id}">{$optionData->category_name}</option>
{/foreach}
	</select>
	이름<input type="checkbox" name="sname" />
	제목<input type="checkbox" name="ssubject" />
	본문<input type="checkbox" name="snote" />
	</td>
	<td>
	검색어&nbsp;<input type="text" name="search" size="10" class="inputtext" style="height:20px;" />
	</td>
	<td>
	<input type="submit" name="mode" value="Search" class="button" />
	</td>
</tr>
</table>
</form>
<br />
<br />
<table width="100%" cellspacing="0" border="0" align="center" valign="top">
<tr>
	<td valign="top" width="50%">
{* 서적 리스트 본문 *}
{foreach item=tdData from=$listData}
	<table width="98%" cellpadding="4" cellspacing="0" border="0" class="text" align="center" valign="top">
		<tr>
			<td colspan="2"><a href="view.php?tableID={$tableID}&amp;id={$tdData->id}&amp;startPage={$nowPage}&amp;sortData={$nowSortData}&amp;searchcrc={$searchcrc}&amp;kind={$kind}"><b>{$tdData->title}</b></a> 
{* 코멘트 갯수 *}
{* 코멘트 디자인 수정시 PM(Pare coMment) 밖으로 수정할것 *}
{if $tdData->subNum != 0}
				<span class="ver6">(<!--PM="{$tdData->id}"-->{$tdData->subNum}<!--PM-->)</span>
{else}
				<span class="ver6">(<!--PM="{$tdData->id}"-->0<!--PM-->)</span>
{/if}
		</td></tr>
		<tr><td background="/images/div_grad.gif" colspan="2"><img src="/images/t.gif" alt="" border="0" width="1" height="4"></td></tr>
		<tr>
			<td valign="top">
				<img border="1" src="imgview.php?tableID={$tableID}&amp;fileid={$tdData->file_id}&amp;resize=true" align="left" style="margin-right:5px" />
				<font color="#008100"><b>Category</b></font> : {$tdData->category_name}<br />
				<font color="#008100"><b>URL</b></font> : <a href="http://{$tdData->extend1}" target="_Blank">{$tdData->extend1}</a><br />
				{$tdData->note}	
			</td>
		</tr>
		<tr>
			<td height="" align="right" colspan="2" valign="bottom">
				<span class="ver7" style="color:#555555">{$tdData->date|date_format:"%y-%m-%d"}&nbsp;by</span>
{* User와 비유저의 구분 혹은 User 정보 링크 *}
{if $tdData->usrid!='' && $tdData->usrid!='1500'}
<a href="/jaram/memberinfo/?gid={$tdData->usrid}">{$tdData->user_name}</a>
{else}
{$tdData->name}
{/if}
				<br />
			</td>	
		</tr>
	</table>
{/foreach}
	</td>
</tr>
</table>
{* 게시물 체크 if *}
{/if}
<img src="/images/t.gif" width="1" height="10" alt="" /><br />

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
<form name ="gopage" action="javascript:goto_page('?tableID={$tableID}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;search={$search}&amp;sortData={$nowSortData}&amp;kind={$kind}&amp;startPage=');" method="get">
<tr>
	<td height="5" colspan="2"></td>
</tr>
<tr>
	<td width="90%">
{* 게시물이 없는 위와 같은 경우 페이징을 보일필요 없다. *}
{if $listData!=''}
	page : 
	{* 페이지 이동 부분  *}
	{if $preViewStart != ""}
	<a href="bbs.php?tableID={$tableID}&amp;startPage={$preViewStart}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}&amp;kind={$kind}">[prev]</a>&nbsp;
	{/if}
	{foreach item=pagePrint from=$pagePrintArray}
	{if $nowPage == $pagePrint}
	&nbsp;<span class="a12b">{$pagePrint}</span>&nbsp;
	{else}
	&nbsp;<a href="bbs.php?tableID={$tableID}&amp;startPage={$pagePrint}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}&amp;kind={$kind}">{$pagePrint}</a>&nbsp;
	{/if}
	{/foreach}
	{if $nextViewStart != ""}
	<a href="bbs.php?tableID={$tableID}&amp;startPage={$nextViewStart}&amp;sname={$sname}&amp;ssubject={$ssubject}&amp;snote={$snote}&amp;sortData={$nowSortData}&amp;search={$search}&amp;kind={$kind}">[next]</a>&nbsp;
	{/if}
{/if}
	</td>
	<td width="50%" align="right"><a href="write.php?tableID={$tableID}"><b>Write</b></a></td>
</tr>
</form>
</table>
<br/>