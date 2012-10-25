{msg_form type="info" msg=$notice}
<p>
<form action="./?page=one_line" method="post" id="one_line_form">
<input type="hidden" name="action" value="post"/>
<input type="hidden" name="msg_id" value="{$msg.msg_id}"/>
<dl>
<dt>
메시지 표시일
</dt>
<dd>
{html_select_date time=$msg.start_date}
부터 <input type="text" name="period" class="input center" size="1" value="{if $msg.period}{$msg.period}{else}{$period}{/if}"/> 일 동안
</dd>
<dt>
한 줄 메시지
</dt>
<dd>
<input type="text" class="input fullSize" name="msg" size="80" value="{$msg.msg_text}"/>
</dd>
<dt>
</dt>
<dd>
<select name="msg_type">
	{html_options options=$msg_type_options selected=$msg.msg_type}
</select>
대상 
<select name="gid">
	{html_options options=$group_options selected=$msg.target_gid}
</select>
<select name="pid">
	<option value="0">모든 페이지</option>
	{html_options options=$program_options selected=$msg.target_pid}
</select>

<input type="submit" name="ok" value="입력"/>
</dd>
</dl>
</form>
</p>

<script type="text/javascript">
<!--
Form.focusFirstElement('one_line_form');
-->
</script>

<p></p>
<table width="100%" cellspacing="0" border="0" id="fancy_table">
<caption><img src="./images/icons/note.gif" border="0"/> 메시지 목록</caption>
<thead>
	<tr>
		<th width="10%" class="title_left">MsgId</th>
		<th width="10%">Msg Type</th>
		<th width="20%">Msg Period</th>
		<th width="50%">Message Text</th>
		<th width="10%" class="title_right">Actions</th>
	</tr>
</thead>
<tbody class="mark_line">
{foreach from=$msgs item=curr_msg}
<tr>
<td class="center sub">{$curr_msg.msg_id}</td>
<td class="center sub">{$curr_msg.msg_type}</td>
<td class="center sub">{$curr_msg.start_date|date_format:"%Y-%m-%d"}~{$curr_msg.end_date|date_format:"%Y-%m-%d"}</td>
<td>{if $curr_msg.msg_type eq 'die'}<del>{$curr_msg.msg_text}</del>{else}{$curr_msg.msg_text}{/if}</td>
<td class="center">
<a href="./?page=one_line&amp;action=delete&amp;msg_id={$curr_msg.msg_id}" class="button"><img src="./images/icons/delete.gif" title="삭제" alt="삭제"/></a>
<a href="./?page=one_line&amp;action=list&amp;msg_id={$curr_msg.msg_id}" class="button"><img src="./images/icons/note_edit.gif" title="수정" alt="수정"/></a>
</td>
</tr>
{/foreach}
</tbody>
</table>

<div class="pager">
{$links.all}
</div>
