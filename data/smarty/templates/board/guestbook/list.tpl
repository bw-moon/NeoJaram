{* smarty *}
{include_php file="bbs_script.html"}
{* �Խ��� header *}
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
{* �������� ������ ��ġ�� ��� �ų� �˻��� ���� �Խù��� ���ų� �� �Խ����� ��� *}
{if $listData==''}
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
<tr>
	<td height="1" colspan="7" align="center">�Խù��� �����ϴ�.</td>
</tr>	
<tr>
	<td height="1" colspan="7" bgcolor="#CCCCCC"></td>
</tr>	
{else}

{* �Խ��� ����Ʈ ���� *}
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
{* ��� ���� ���� *}
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
{* User�� ���� ��ũ�ȴ�. Ȥ�� Ư�� �����Ѵ�. *}
{if $tdData->usrid!='' && $tdData->usrid!='1500'}
	<a href="/jaram/memberinfo/?gid={$tdData->usrid}">{$tdData->user_name}</a>
{else}
{$tdData->name}
{/if}
	</td>
{* ��¥ ����� ��-��-��, Title�� ������ *}
	<td align="center"><font title="{$tdData->date|date_format:"%Y��, %m��(%B), %A, %e��\n%r"}">{$tdData->date|date_format:"%y-%m-%d"}</font></td>
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
{* �Խù��� ���� ���� ���� ��� ����¡�� �����ʿ� ����. *}
{if $listData!=''}
{* ������ �̵� �κ�  *}
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

{* �˻� �κ� *}
<form name="search" method="post" action="bbs.php?tableID={$tableID}">
<table border="0" width="100%" cellpadding="0" cellspacing="0" class="text" align="center">
<tr>
	<td><a href="/?page=monitor_add&amp;tableID={$tableID}">Monitor This Board</a></td>
	<td align="right">
		<table border="0" cellpadding="3" cellspacing="0" class="text">
		<tr><td>
		�̸�<input type="checkbox" name="sname" />
		����<input type="checkbox" name="ssubject" />
		����<input type="checkbox" name="snote" />
		</td>
		<td>�˻���&nbsp;<input type="text" name="search" size="10" class="inputtext" style="height:20px;" /></td>
		<td><input type="image" name="mode" value="Search" src="/images/button/btn_search.gif" /></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>