{* smarty *}
{include_php file="write_script.html"}
{if $loginCheck == ""}
{include_php file="write_script_logout.html"}
{else}
{include_php file="write_script_login.html"}
{/if}
<div align="center">
<form name="flistResult" enctype="multipart/form-data" method="post" action="upload_result.php">
<textarea name="fileDataOut" rows="1" cols="1" style="position:absolute; left:-400px; top:-400px; z-index:1; visibility:hidden;"></textarea>
<input type="hidden" name="tableID" value="{$tableID}" />
<input type="hidden" name="replyID" value="{$replyID}" />
<input type="hidden" name="modID" value="{$modID}" />
<input type="hidden" name="mtime" value="{$mtime}" />
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="text">
{if $loginCheck == ""}
<tr>
	<td align="right" width="80"><b>Name</b></td>
	<td><input type="text" name="name" size="10"  maxlength="20" class="inputtext" value="{$dataValue->name}" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>Password</b></td>
	<td><input type="password" name="passwd" size="20" class="inputtext" maxlength="20" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>E-mail</b></td>
	<td><input type="text" name="email" size="40" class="inputtext" maxlength="200" value="{$dataValue->email}" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>Homepage</b></td>
	<td><input type="text" name="homepage" size="40" class="inputtext" maxlength="200" value="{$dataValue->homepage}" style="height:20px;" /></td>
</tr>
{/if}
<tr>
	<td align="right" width="80"><b>Subject</b></td>
	<td>
{* 답변글시 Title을 손보기 *}
{if $USING_REPLY !=""}
		<input type="text" name="title" size="40" maxlength="200" class="inputtext" value="Re : {$dataValue->title}" style="width:100%;height:20px;" />
{else}
		<input type="text" name="title" size="40" maxlength="200" class="inputtext" value="{$dataValue->title}" style="width:100%;height:20px;" />
{/if}
	</td>
</tr>
<tr>
	<td align="right"><b>URL</b></td>
	<td><input type="text" name="extend1" size="40" class="inputtext" maxlength="200" value="{$dataValue->extend1}" style="height:20px;" /></td>
</tr>
{* 카테로그 선택시 뿌려주기 *}
{if $categoryData !=""}
<tr>
	<td align="right"><b>Category</b></td>
	<td>
		<select name="kind" class="inputtext" class="inputtext">
{foreach item=optionData from=$categoryData}
{if $dataValue->category==$optionData->id}
			<option  value="{$optionData->id}" selected>{$optionData->category_name}</option>
{else}
				<option  value="{$optionData->id}">{$optionData->category_name}</option>
{/if}
{/foreach}
		</select>
	</td>
</tr>
{/if}

<tr>
	<td align="right"><b>Contents</b></td>
	<td valign="top">
		<textarea name="note" cols="40" class="inputtext" rows="20" style="width:100%;">{if $USING_REPLY !=""}
---------- ---------- ----------
{$dataValue->note|indent:5}
{else}
{$dataValue->note}
{/if}</textarea>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><b>Maximum File size : 4MB</b></td>
</tr>
</table>
</form>
</div>
<iframe name="POPWIN" id="ATTMSG" style="position:absolute; left:-400px; top:-400px; width:334x; height:55px; z-index:1; visibility:hidden;" scrolling="no" frameborder="0" width="334" height="55"></iframe>
<form target="POPWIN" name="fupload" enctype="multipart/form-data" method="post" action="upload.php?tableID={$tableID}">
{* 썸네일용 *}
<input type="hidden" name="USING_PREVIEW_IMG" value="{$USING_PREVIEW_IMG}" />
<input type="hidden" name="PREVIEW_IMG_X" value="{$PREVIEW_IMG_X}" />
<input type="hidden" name="PREVIEW_IMG_Y" value="{$PREVIEW_IMG_Y}" />
<table border="0" cellspacing="0" cellpadding="3" class="text">
<tr>
	<td width="80" align="right"><b>Browse</b></td>
	<td colspan="2"><input type="file" value="Browse" size="35" name="attach_file" class="inputtext" style="height:20px" /></td>
</tr>
<input type="hidden" name="fileSizeTemp" />
<textarea name="fileDataIn" rows="1" cols="1" style="position:absolute; left:-400px; top:-400px; z-index:1; visibility:hidden;"></textarea>
</form>
<form target="POPWIN" name="flist" enctype="multipart/form-data" method="post" action="upload_delete.php">
<input type="hidden" name="fileTempDel" />
<tr>
	<td valign="top" align="right"><b>File List</b></td>
	<td>
	<select name="file_list" size="5" class="inputtext">
{if $dataValue->file_number}
		<option value="asd">-- 첨부된 현 파일 목록 수 : {$dataValue->file_number} --</option>
{else}
		<option value="asd">---- 첨부될 파일목록 -----</option>
{/if}
	</select>
	</td>
	<td valign="top"><input type="button" value="Append" onclick="file_attach();" class="button" /><br /><br />
	<input type="button" value="Remove" onclick="selectBoxFileDel();" class="button" /></td>
</tr>
<tr>
	<td align="right"><b>total size</b></td>
	<td colspan="2"><input type="text" name="fileSizeAll" size="10" readonly="true" class="inputtext" style="height:20px;" /></td>
</tr>
</form>
</table>
<br />
<div align="center">
<input type="button" value="Post Article" onclick="check_submit();" class="button"> <input type="button" value="Cancel" onclick="javascript:history.back(-1);" class="button">
</div>
<br />