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
	<td align="right" width="70"><b>Name</b></td>
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
	<td align="right" width="70"><b>Title</b></td>
	<td>
{* 답변글시 Title을 손보기 *}
{if $USING_REPLY !=""}
		<input type="text" name="title" size="40" maxlength="200" class="inputtext" value="Re : {$dataValue->title}" style="width:100%;height:20px;" />
{else}
		<input type="text" name="title" size="40" maxlength="200" class="inputtext" value="{$dataValue->title}" style="width:100%;height:20px;" />
{/if}
	</td>
</tr>
{literal}
<script language="javascript">
function set_extend1()
{
	document.flistResult.extend1.value=document.flistResult.ex11.value+"\|\|"+document.flistResult.ex12.value;
}
function set_extend2()
{
	document.flistResult.extend2.value=document.flistResult.ex21.value+"\|\|"+document.flistResult.ex22.value;
}
</script>
{/literal}
<input type="hidden" name="extend1" />
<input type="hidden" name="extend2" />
<tr>
	<td align="right"><b>Author</b></td>
	<td><input type="text" name="ex11" size="40" class="inputtext" maxlength="200" value="{$dataValue->extend1|regex_replace:"/\|\|.*/":""}" onKeyUp="set_extend1();" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>Translator</b></td>
	<td><input type="text" name="ex12" size="40" class="inputtext" maxlength="200" value="{$dataValue->extend1|regex_replace:"/.*\|\|/":""}" onKeyUp="set_extend1();" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>Publisher</b></td>
	<td><input type="text" name="ex21" size="40" class="inputtext" maxlength="200" value="{$dataValue->extend2|regex_replace:"/\|\|.*/":""}" onKeyUp="set_extend2();" style="height:20px;" /></td>
</tr>
<tr>
	<td align="right"><b>ISBN</b></td>
	<td><input type="text" name="ex22" size="40" class="inputtext" maxlength="200" value="{$dataValue->extend2|regex_replace:"/.*\|\|/":""}" onKeyUp="set_extend2();" style="height:20px;" />
	&nbsp;&nbsp; - 없이 숫자만 입력해 주세요
	</td>
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
</table>
</form>
</div>
<div align="center">
<a href="javascript:set_extend1();javascript:set_extend2();javascript:check_submit();"><img src="/images/button/btn_post.gif" border="0"/></a>&nbsp;&nbsp;<a  href="javascript:history.back(-1);"><img src="/images/button/btn_cancel.gif" border="0"/></a>
</div>
<br />