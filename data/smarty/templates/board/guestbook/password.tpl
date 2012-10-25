{* smarty *}
{* password 확인용 템플릿 *}
<body bgcolor="#E6F1FB">
{if $typeID=="1"}
<form name="fpass" method="post" action="delete_comment.php">
{elseif $typeID=="2"}
<form name="fpass" method="post" action="delete_note.php">
{elseif $typeID=="3"}
<form name="fpass" method="post" action="modify_note.php">
{/if}
<input type="hidden" name="tableID" value="{$tableID}" />
<input type="hidden" name="id" value="{$recordID}" />
<input type="hidden" name="superid" value="{$superID}" />
<input type="hidden" name="startPage" value="{$startPage}" />
<table width="100%" height="100%" border="1" cellpadding="3" cellspacing="0" class="text" align="center">
<tr>
	<td>
	PASSWORD 입력해 주세요.
	</td>
</tr>
<tr>
	<td>
	<input type="password" name="passwd" size="10" style="height:20px;" />
	</td>
</tr>
</table>
</form>
</body>