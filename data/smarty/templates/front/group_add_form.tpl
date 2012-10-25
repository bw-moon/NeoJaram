<a href="./?page=group_list">그룹 관리</a> | <a href="./?page=group_add">새로운 그룹 추가</a> | <a href="./?page=group_permit">그룹 관리(old)</a>

{msg_form type=$msg_type msg=$msg}


{if $mode eq 'append'}
<p>
새로운 그룹을 생성합니다.<br/>
그룹을 생성자는 기본적으로 그룹의 관리자로 등록이 되며, 그룹원 가입승인 및 삭제, 그룹정보 변경 등의 작업을 수행할 수 있습니다.
</p>
{/if}

<form action="./?page=group_add" method="post" id="group_add_form">
<input type="hidden" name="gid" value="{$smarty.request.gid}"/>
<input type="hidden" name="page" value="{$smarty.get.page}"/>
<input type="hidden" name="mode" value="{$mode}"/>
<dl>
<dt>그룹 이름:</dt>
<dd><input type="text" class="text" size="20" name="group_name" value="{$group.group_name}"/></dd>
<dt>그룹 설명:</dt>
<dd><input type="text" class="text fullSize" size="40" name="group_desc" value="{$group.group_description}"/></dd>
<dt></dt>
<dd><input type="submit" name="add_ok" value="{$mode}"/></dd>
</dl>
</form>