<p>
{'link'|icon} 바로가기 : {$line_link}
</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="fancy_table">
<thead>
	<tr>
		<th class="title_left" width="100">Real Name</th>
		{if $is_login}
		<th>Login Name</th>
		{assign var="colspan" value=4}
		{/if}
		<th>Email Address</th>
		<th  class="title_right" width="200">Homepage</th>
	</tr>
</thead>
<tbody>
{foreach from=$group_list item=member}
{if $member.user_number ne $last_member.user_number}
<tr>
	<td colspan="{$colspan|default:"3"}" class="sub_title">
		<h3><a name="{$member.user_number}">{$member.user_number}기</a> <a href="#" class="sub">[top]</a></h3>
	</td>
</tr>
{/if}
<tr>				
	<td align="center"><a href="{$context_root}/jaram/memberinfo/?gid={$member.uid}">{$member.user_name}</a></td>
	<td align="center" class="sub"><a href="{$context_root}/jaram/memberinfo/?gid={$member.uid}">{$member.user_id}</a></td>
{if $is_login}
	<td>{$member.user_email}</td>
{/if}
	<td>{$member.user_homepage}</td>
</tr>
{assign var="last_member" value=$member}
{/foreach}
</tbody></table>
