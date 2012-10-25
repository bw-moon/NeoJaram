{* smarty *}
{include_php file="view_script.html"}
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr>
	<td class="a12b">No.{$id}</td>
	<td align="right">{$viewData->date|date_format:"%A, %B %e, %Y"}</td>
</tr>
<tr>
	<td bgcolor="#555555" height="1" colspan="2"></td>
</tr>
<tr>
	<td colspan="2" bgcolor="#f5f5f5"><b>Name</b> : 
{* User와 비유저의 구분 혹은 User 정보 링크 *}
{if $viewData->usrid!='' && $viewData->usrid!='1500'}
	<a href="/jaram/memberinfo/?gid={$viewData->usrid}">{$viewData->user_name}</a>
{else}
{$viewData->name}
{/if}
	</td>
</tr> 
<tr>
	<td colspan="2" bgcolor="#f5f5f5"><b>Subject</b> : {$viewData->title}</td>
</tr>
<tr>
	<td bgcolor="#bbbbbb" height="1" colspan="2"></td>
</tr>
</table>

{if $fileData}
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="text">
<tr>
	<td colspan="2"><b>Downloads</b><br />
<ol style="margin: 0px 0px 0px 30px;">
{* 파일 Download *}
{foreach item=fileList from=$fileData}
<li /><a href="download.php?tableID={$tableID}&amp;fileid={$fileList->file_id}">{$fileList->file_name}</a>
&nbsp;(Download : {$fileList->file_count} &nbsp;&nbsp; 파일크기 : {$fileList->file_size}Byte)
<br />
{/foreach}
</ol>
	</td>
</tr>	
<tr>
	<td>
{* 업로드된 이미지 뿌려주기 *}
{foreach key=key item=imgList from=$imgData}
{if $imgList[0]>500 || $imgList[1]>500}
<a href="#" onClick="javascript:window.open('imgview.php?tableID={$tableID}&amp;fileid={$key}','','resizable=yes, scrollbars=auto, width=screen.availWidth, height=screen.availHeight');"><img src="imgview.php?tableID={$tableID}&amp;fileid={$key}" width="400" alt="" border="1"/></a>
{else}
<a href="#" onClick="javascript:window.open('imgview.php?tableID={$tableID}&amp;fileid={$key}','','scrollbars=auto, width={$imgList[0]}, height={$imgList[1]}')"><img src="imgview.php?tableID={$tableID}&amp;fileid={$key}" width="{$imgList[0]}" height="{$imgList[1]}" border="1" alt=""></a>
{/if}
<br />
{/foreach}
	</td>
</tr>
</table>
{/if}

<table width="100%" cellpadding="0" cellspacing="3" border="0" class="text">
<tr>
	<td>{$viewData->note|nl2br}</td>
	</tr>	
</table>

<img src="/images/t.gif" width="1" height="5" alt="" /><br />
{if $commentData!=""}
<table width="100%" cellspacing="7" cellpadding="0" border="0" class="text">
<tr>
	<td colspan="4"><b>Comments</b></td>
</tr>
<tr>
	<td colspan="4" height="1" bgcolor="#efefef"></td>
</tr>
{foreach item=tdData from=$commentData}
<tr>
	<td width="50" valign="top">
{* User와 비유저의 구분 혹은 User 정보 링크 *}
{if $tdData->usrid!='' && $tdData->usrid!='1500'}
<a href="/jaram/memberinfo/?gid={$tdData->usrid}">{$tdData->user_name}</a>
{else}
{$tdData->name}
{/if}
	</td>
	<td width="5" bgcolor="#efefef"></td>
	<td>{$tdData->note|nl2br}</td>
	<td width="60" align="right" valign="top"><a href="delete_comment.php?tableID={$tableID}&amp;superid={$id}&amp;id={$tdData->id}&amp;startPage={$startPage}" target="iframe_password" onclick="view_iframe();" title="{$tdData->date|date_format:"%Y년, %m월(%B), %A, %e일\n%r"}">{$tdData->date|date_format:"%y-%m-%d"}</td>
</tr>
<tr>
	<td colspan="4" height="1" bgcolor="#efefef"></td>
</tr>
{/foreach}
</table>
{/if}
<iframe name="IFCOM" id="ATTMSG" style="position:absolute; left:-400px; top:-400px; width:334x; height:55px; z-index:1; visibility:hidden;" scrolling="no" frameborder="0" width="10" height="10"></iframe>
<br/>
<form target="IFCOM" name="fcomment" method="post" action="write_comment.php" style="margin: 0px 0px 0px 0px;">
<input type="hidden" name="tableID" value="{$tableID}" />
<input type="hidden" name="s_id" value="{$id}" />
<input type="hidden" name="startPage" value="{$startPage}" />
<input type="hidden" name="sortData" value="{$sortData}" />
</form><br/>

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="text">
	<tr>
		<td align="right">
		<b><a href="write.php?tableID={$tableID}&amp;replyID={$id}">Reply</a></b>&nbsp;&nbsp;
		<b><a href="modify_note.php?tableID={$tableID}&amp;superid={$startPage}&amp;id={$id}" target="iframe_password" onclick="view_iframe();" >Modify</a></b>&nbsp;&nbsp;
		<b><a href="delete_note.php?tableID={$tableID}&amp;superid={$startPage}&amp;id={$id}" target="iframe_password" onclick="view_iframe();" >Delete</a></b>&nbsp;&nbsp;
		<b><a href="delete_note.php?tableID={$tableID}&amp;superid={$startPage}&amp;id={$id}&amp;spam=true" target="iframe_password" onclick="view_iframe();" title="Spam 처리합니다">Spam</a></b>&nbsp;&nbsp;
		<b><a href="bbs.php?tableID={$tableID}&amp;startPage={$startPage}&amp;sortData={$sortData}">List</a></b>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<br />