<a href="./?page=group_list">그룹 관리</a> | <a href="./?page=group_add">새로운 그룹 추가</a> | <a href="./?page=group_permit">그룹 관리(old)</a>

<table width="100%" cellpadding="3" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/group_go.gif" border="0"/> 승인 대기중</caption>
<thead>
	<tr>
		<th width="20%" class="title_left">Group Name</th>
		<th width="50%">Group Description</th>
		<th width="20%">User Name</th>
		<th width="10%" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach from=$group_accept_wait item=row}
	<tr>
		<td align="center"><a href="./jaram/memberinfo/?gid={$row.gid}">{$row.group_name}</a></td>
		<td>{$row.group_description}</td>
		<td align="center">{$row.user_name} ({$row.user_id})</td>
		<td align="center"><a href="./?page=group_permit&amp;mode=accept&amp;gid={$row.gid}&amp;uid={$row.uid}" class="button sub"><img src="./images/icons/accept.gif" border="0" alt="accept" title="가입 승인"/></a>
		<a href="./?page=group_join&amp;mode=cancel&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/delete.gif" border="0" alt="cancel" title="가입 취소"/>
		</td>
	</tr>
{/foreach}
{if not $group_accept_wait}
<tr>
	<td colspan="4" align="center">내용이 없습니다.</td>
</tr>
{/if}
</tbody>
</table>



<table width="100%" cellpadding="3" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/group_add.gif" border="0"/> 가입 대기중</caption>
<thead>
	<tr>
		<th width="20%" class="title_left">Group Name</th>
		<th width="70%">Group Description</th>
		<th width="10%" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach from=$group_join_wait item=row name=group_join_wait}
<tr>
	<td align="center"><a href="./jaram/memberinfo/?gid={$row.gid}">{$row.group_name}</a></td>
	<td>{$row.group_description}</td>
	<td align="center"><a href="./?page=group_join&amp;mode=cancel&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/delete.gif" border="0" alt="cancel" title="가입 취소"/></td>
</tr>
{/foreach}
{if not $group_join_wait}
<tr>
	<td colspan="3" align="center">내용이 없습니다.</td>
</tr>
{/if}
</tbody>
</table>

<table width="100%" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/group.gif" border="0"/> 전체 그룹 목록 <span class="sub">: 개인 그룹은 제외 (gid:1300~2999)</span></caption>
<thead>
	<tr>
		<th width="20%" class="title_left">Group Name</th>
		<th width="70%">Group Description</th>
		<th width="10%" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach from=$group_total item=row}
	<tr>
		<td class="text" align="center"><a href="./jaram/memberinfo/?gid={$row.gid}">{$row.group_name}</a></td>
		<td class="text">{$row.group_description}</td>
		<td class="text" align="center">
{if $row.id}
		<a href="./?page=group_join&amp;mode=leave&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/door_out.gif" border="0" alt="leave" title="탈퇴"/></a>
{else}
		<a href="./?page=group_join&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/user_add.gif" border="0" alt="join" title="가입"/></a>
{/if}
{if $row.status eq "o"}
		<a href="./?page=group_permit&amp;mode=del_group&amp;gid={$row.gid}" class="button sub"><img src="./images/icons/bin.gif" border="0" alt="delete" title="그룹삭제"/></a>
		<a href="./?page=group_add&mode=modify&gid={$row.gid}" class="button sub"><img src="./images/icons/group_edit.gif" alt="edit" title="그룹 수정"/></a>
{/if}		
		</td>
	</tr>
{/foreach}
</tbody>
</table>