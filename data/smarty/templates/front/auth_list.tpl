<a href="./?page=auth_management">전체 그룹 목록</a> | <a href="./?page=auth_add">Add New Privilege</a>

<table width="100%" cellpadding="3" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/lock.gif" border="0"/> 권한 목록</caption>
<thead>
	<tr>
		<th width="20%" class="title_left">Group Name</th>
		<th width="50%">Group Description</th>
		<th width="10%" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach from=$groups item=row}
	<tr>
		<td align="center"><a href="./jaram/memberinfo/?gid={$row.gid}">{$row.group_name}</a></td>
		<td>{$row.group_description}</td>
		<td align="center"><a href="./?page=auth_modify&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/lock_edit.gif" border="0" alt="edit" title="권한 수정"/></a>
		</td>
	</tr>
{/foreach}
{if not $groups}
<tr>
	<td colspan="3" align="center">내용이 없습니다.</td>
</tr>
{/if}
</tbody>
</table>
