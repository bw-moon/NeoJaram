<a href="./?page=auth_management">전체 그룹 목록</a> | <a href="./?page=auth_add">Add New Privilege</a>
<p>
Modify Privilege of {$group_info.group_name}({$group_info.group_description})<br/>
<a href="/?page=auth_modify_act&mode=delete_all&gid={$_GET.gid}"><img src="./images/icons/lock_delete.gif" border="0"/> 모든 권한 삭제</a><br/>
</p>
<table width="100%" cellpadding="3" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/lock_edit.gif" border="0"/> 세부 권한 설정</caption>
<thead>
	<tr>
		<th width="20%" class="title_left">프로그램</th>
		<th>View</th>
		<th>Read</th>
        <th>Post</th>
        <th>Comment</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Announce</th>
        <th>Vote</th>
        <th>Upload</th>
		<th colspan="2" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach item=row from=$auth_list}
	<tr>
    <form method="post" name="perm" action="./?page=auth_modify_act">
    <input type="hidden" name="gid" value="{$row.gid}"/>
    <input type="hidden" name="pid" value="{$row.pid}"/>
    <input type="hidden" name="bid" value="{$row.bid}"/>
        <td align="center">{if $row.sub_menu}{$row.sub_menu}{else}{$row.main_menu}{/if}</td>
        <td align="center"><input type="checkbox" name="perm[view]" value="1" {if $row.auth_view}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[read]" value="1" {if $row.auth_read}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[post]" value="1" {if $row.auth_post}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[comment]" value="1" {if $row.auth_comment}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[edit]" value="1" {if $row.auth_edit}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[delete]" value="1" {if $row.auth_delete}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[announce]" value="1" {if $row.auth_announce}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[vote]" value="1" {if $row.auth_vote}checked{/if} /></td>
        <td align="center"><input type="checkbox" name="perm[upload]" value="1" {if $row.auth_upload}checked{/if} /></td>
        <td align="center"><input type="submit" name="act" value="Modify" class="small"/></td>
        <td align="center"><input type="submit" name="act" value="Delete" class="small"/></td>
    </form>
    </tr>
{/foreach}

</tbody>
</table>
