<p>{"rainbow"|icon} 세미나 년도 : {$links}</p>
{foreach from=$seminars item=seminar key=key}
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="fancy_table">
<thead>
	<tr>
		<th width="100" class="title_left">{$key}</th>
		<th>subject</th>
		<th width="80">comments</th>
		<th width="100" class="title_right">speaker</th>
	</tr>
</thead>
<tbody>
{foreach from=$seminar item=row}
	<tr>
		<td align="center">{$row.schedule_start|to_timestamp|date_format:"%e일 "}</td>
		<td><a href="./seminar_view.php?seminar_id={$row.seminar_id}">{$row.seminar_topic}</a></td>
		<td align="center" class="small">{$row.comment_count}</td>
		<td align="center"><a href="{$context_root}/jaram/memberinfo/?gid={$row.seminar_group_id}">{$row.seminar_group_name}</a></td>
	</tr>
{/foreach}
</tbody>
</table>
{/foreach}
{if !$seminars}
	{msg_form type="info" msg="현재년도에 입력된 세미나가 없습니다"}
{/if}