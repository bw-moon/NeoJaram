<table width="100%" border="0">
<tr>
	<td valign="top" width="50%">
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="text">
		<tr>
			<td colspan="2"><h3>Personal Infomation</h3></td>
		</tr>
		<tr>
			<td>사용자 번호:</td>
			<td><b>{$member_info.uid}</b></td>
		</tr>
		<tr>
			<td>사용자 아이디:</td>
			<td><b>{$member_info.user_id}</b></td>
		</tr>
		<tr>
			<td>이름:</td>
			<td><b>{$member_info.user_name}</b></td>
		</tr>
		<tr>
			<td>지금 하고 있는 일:</td>
			<td>휴학중(군휴학-071120전역)</td>
		<tr>
			<td>이메일:</td>
			<td>{$member_info.user_email}</td>
		</tr>
		<tr>
			<td>홈페이지(블로그):</td>
			<td><a href="{$member_info.user_homepage}" target="_blank" class="sub">{$member_info.user_homepage}</a></td>
		</tr>
		<tr>
			<td>휴대폰:</td>
			<td><b>{$member_info.user_phone1}</b></td>
		</tr>
		<tr>
			<td>대표메신저:</td>
			<td>{$member_info.user_msgr_id} ({$member_info.user_msgr_type})
			</td>
		</tr>
		<tr bgcolor="#ffffff" align="left">
			<td colspan="2" bgcolor="#ffffff"><hr width="100%" size="1"/>
			<p>
			{"email"|icon} <a href="{$context_root}/?page=sendmessage&touser={$member_info.uid}" class="a12b">{$member_info.user_name}에게 이메일 보내기</a>
			</p>
			<p>
			이 사용자는 아래 그룹에 속해있습니다:
			</p>
			{foreach from=$user_groups item=group}
				<a href="./?gid={$group.gid}"><strong>{$group.group_name}</strong>{if $group.group_description}({$group.group_description}){/if}</a>,
			{/foreach}
			</td>
		</tr>
	</table>
	</td>
	<td valign="top" width="50%">
	지금까지 작성한 글의 개수 : <a href="" title="내가 작성한 글목록">0건</a><br/>
	월평균 작성건수(첫 글 작성 이후) : 0건<br/>
	지금까지 작성한 글의 양 : <a href="" title="파일 다운로드">0KByte</a>, 400page 책 0권<br/>
	최고 조회수 : 0<br/>
	평균 조회수 : 0<br/>
	가장 많이 본 글 : xxxxx<br/>
	보유기술 : Python(중), C(중), C++(하), Network Programming(중), Unix Maintanance(중), Technical Writing(중), Technical Translation(하), PHP(상), Javascript(중)<br/>
	경력 :<br/>
	<ul>
		<li>2004.03.11~2005.02.11 : (주) 와이즈그램 - 연구원</li>
		<li>2005.03.11~2007.02.11 : 국방부 합참 KJCCS개발실 - 소프트웨어 개발병</li>
	</ul>
	</td>
</tr>
</table>
