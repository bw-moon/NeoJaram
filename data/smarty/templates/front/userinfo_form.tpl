<form name="Feditinfo" enctype="multipart/form-data" method="post" action="./member/editinfo2.php">
<table width="100%" cellpadding="3" cellspacing="0">
	<tr>
		<th width="160">사용자 번호</th>
		<td><b>{$rows.uid}<b></td>
	<tr>
	<tr>
		<th>로그인 아이디</th>
		<td><input name="id" type="input" size="15" maxlength="12" value="{$rows.user_id}" /><b></td>
	<tr>
	<tr>
		<th>이름</th>
		<td><input name="name" type="input" size="15" maxlength="12" value="{$rows.user_name}" /></td>
	<tr>
	<tr>
		<th>지금 하는 일</th>
		<td><input name="job" id="job" type="input" size="30" maxlength="12" value="{$rows.user_job_tag_id|to_tagname}" />
		<div id="job2update" style="display:none;border:1px solid black;background-color:white;"></div>
		</td>
	<tr>
	<tr>
		<th>기존 비밀번호</th>
		<td><input name="old_pass" type="password" size="15" maxlength="12" /> 비밀번호를 변경하실 때만 넣어주세요.</td>
	</tr>
	<tr>
		<th>새 비밀번호</th>
		<td><input name="pass" type="password" size="15" maxlength="12" /></td>
	</tr>
	<tr>
		<th>새 비밀번호(재입력)</th>
		<td><input name="re_pass" type="password" size="15" maxlength="12" /></td>
	</tr>
	<tr>
		<th>생년월일</th>
		<td><input name="birthday" type="text" size="20" maxlength="20" value="{$rows.user_birthday}" /> 예) 19830309</td>
	</tr>
	<tr>
		<th>이메일 주소</th>
		<td><input name="email" type="text" size="50" maxlength="255" value="{$rows.user_email}" /></td>
	</tr>
	<tr>
		<th>홈페이지(or 블로그)</th>
		<td><input name="homepage" type="text" size="50" maxlength="255" value="{$rows.user_homepage}" /></td>
	</tr>
	<tr>
		<th>휴대폰</th>
		<td><input name="phone1" type="text" size="50" maxlength="100" value="{$rows.user_phone1}" /></td>
	</tr>
	<tr>
		<th>메신저</th>
		<td>
		{html_options name="msgr_type" options=$msgr_type selected=$rows.user_msgr_id}
		<input name="msgr_id" type="text" size="40" maxlength="255" value="{$rows.user_msgr_id}" />
		</td>
	</tr>
	<tr>
		<th>보유기술</th>
		<td>
			<ul id="skill_inventory">
				{foreach from=$skill_tags item=tag}
					<li id="{$tag.tag_use_field}_{$tag.tag_use_id}">{$tag.tag_name} <a href="javascript:delete_tag('{$tag.tag_use_field}', '{$tag.tag_use_id}')">{'cross'|icon}</a></li>
				{/foreach}
			</ul>
			<input type="text" size="40" id="skill" name="skill"/> <input type="button" value="추가" onclick="add_tag('skill', 'skill', 'user_skill', 'skill_inventory')"/>
			<div id="skill2update" style="display:none;border:1px solid black;background-color:white;"></div>
		</td>
	</tr>
	<tr>
		<th>주요이력</th>
		<td>
			<textarea name="career" rows="5" cols="40"></textarea>
		</td>
	</tr>
	<tr>
		<th>기본 이미지</th>
		<td><input name="profileMain" type="file" /> 프로필이나, 작성한 글에 표시됩니다. 
		{if $rows.user_having_image1 eq 'true'}<p><img src="{$user->getUserPic()}"/></p>{/if}
        </td>
	</tr>
	<tr>
		<th>코멘트 이미지</th>
		<td><input name="profileSub" type="file" /> 입력한 코멘트에 표시됩니다. (미입력시 기본 이미지 사용)
		{if $rows.user_having_image2 eq 'true'}<p><img src="{$user->getSmallPic()}"/></p>{/if}
		</td>
	</tr>
	<tr>
		<th>서명</th>
		<td><textarea name="sign" cols="49" rows="5" class="fullSize">{$rows.user_sign}</textarea></td>
	</tr>

    <tr>
		<td colspan="2" align="right"><input type="submit" value="사용자 정보수정"></td>
	</tr>
</table>
</form>

<script type="text/javascript" language="javascript" charset="utf-8">
// <![CDATA[
  new Ajax.Autocompleter('skill','skill2update',server_url,{$smarty.ldelim}parameters:'action=get_tag&field=skill&form_field=job'{$smarty.rdelim});
  new Ajax.Autocompleter('job','job2update',server_url,{$smarty.ldelim}parameters:'action=get_tag&field=job&form_field=job'{$smarty.rdelim});
//Effect.Fade('user_skill_9');
// ]]>
</script>