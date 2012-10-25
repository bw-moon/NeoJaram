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
	<td width="100" bgcolor="#f5f5f5" align="center" valign="top">
	{if $viewData->extend2|regex_replace:"/.+\|\|/":""|truncate:2:""=="89"}
		    {if $viewData->extend2|regex_replace:"/.+\|\|/":""|truncate:3:""=="899"}
                <img border="1" src="http://image.aladdin.co.kr/cover/cover/{$viewData->extend2|regex_replace:"/.+\|\|/":""}_1.jpg" style="margin:5px"/>
            {else}
                <img border="1" src="http://image.aladdin.co.kr/cover/cover/{$viewData->extend2|regex_replace:"/.+\|\|/":""}_1.gif" style="margin:5px"/>
            {/if}
	{else}
		<img border="1" src="http://images.amazon.com/images/P/{$viewData->extend2|regex_replace:"/.+\|\|/":""}.01.MZZZZZZZ.gif" style="margin:5px"/>
	{/if}
	</td>
	<td bgcolor="#f5f5f5" style="line-height:180%" valign="top">
	<b>Subject</b> : {$viewData->title}<br/>
	<b>Category</b> : {$viewData->category_name}<br/>
	<b>Author</b> : {$viewData->extend1|regex_replace:"/\|\|.*/":""}<br/>
	{if $viewData->extend1|regex_replace:"/.*\|\|/":"" !=""}
		<b>Translator</b> : {$viewData->extend1|regex_replace:"/.*\|\|/":""}<br/>
	{/if}
	<b>Publisher</b> : {$viewData->extend2|regex_replace:"/\|\|.*/":""}<br/>
	<b>Reviewer</b> : <a href="/jaram/memberinfo/?gid={$viewData->usrid}">{$viewData->user_name}</a><br/>
	{if $viewData->extend2|regex_replace:"/.*\|\|/":""|truncate:2:""=="89"}
		<a href="http://www.aladdin.co.kr/catalog/book.asp?ISBN={$viewData->extend2|regex_replace:"/.*\|\|/":""}" target="_blank">[View detail infomation]</a>
	{else}
		<a href="http://www.amazon.com/exec/obidos/ISBN={$viewData->extend2|regex_replace:"/.*\|\|/":""}" target="_blank">[View detail information]</a>
	{/if}
	</td>
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
</table>
{/if}

<table width="100%" cellpadding="5" cellspacing="0" border="0" class="text">
<tr>
	<td valign="top" >
	{$viewData->note|nl2br}
	</td>
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
{if $tdData->usrid!='' || $tdData->usrid!='1500'}
<a href="/jaram/memberinfo/?gid={$viewData->usrid}">{$tdData->user_name}</a>
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
<table width="100%" cellspacing="0" cellpadding="3"  border="0" class="text">
{if $loginCheck=='' || $loginCheck=='nonuser'}
	<tr>
		<td width="40" align="center"><b>name</b></td>
		<td width="50"><input type="text" name="s_name" value="{$name}" size="10" maxlength="20" style="font-family:tahoma;font-size:11px;font-weight:bold;border:1 solid #CBCBCB;width:80;height:20px"/></td>
		<td width="80" align="right"><b>password</b></td>
		<td><input type="password" name="s_pass" size="8" style="font-family:tahoma;font-size:11px;font-weight:bold;border:1 solid #CBCBCB;width:80;height:20px"/></td>
		<td></td>
	</tr>
{/if}
	<tr>
		<td width="40" align="center"><b>text</b></td>
		<td colspan="3"><textarea name="s_note" cols="35" rows="6" style="width:100%;height:100px;border:1 solid #CBCBCB" class="text"></textarea></td>
		<td width="100"><input type="button" onclick="check_submit();" value="Post" style="width:100px;height:100px" class="input"></td>
	</tr>
</table>
</form><br/>

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="text">
	<tr>
		<td align="right">
		<a href="modify_note.php?tableID={$tableID}&amp;superid={$startPage}&amp;id={$id}" target="iframe_password" onclick="view_iframe();" ><b>Modify</b></a>&nbsp;&nbsp;
		<a href="delete_note.php?tableID={$tableID}&amp;superid={$startPage}&amp;id={$id}" target="iframe_password" onclick="view_iframe();" ><b>Delete</b></a>&nbsp;&nbsp;
		<a href="bbs.php?tableID={$tableID}&amp;startPage={$startPage}&amp;sortData={$sortData}"><b>List</b></a>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<br />
