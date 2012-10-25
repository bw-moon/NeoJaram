<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>{$document_title}</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="Content-Language" content="ko-KR" />
<meta name="Generator" content="EditPlus" />
<meta name="Author" content="J@Web" />
<meta name="Description" content="한양대학교 전자전기컴퓨터공학부 전산전공학회 자람입니다." />
<!-- 공통 스타일 시트 -->
{insert_css files=$import_css}
<!-- 공통으로 쓰일 자바스크립트 -->
<script language="JavaScript" type="text/JavaScript" src="{$context_root}/js/prototype.js"></script>
<script language="JavaScript" type="text/JavaScript" src="{$context_root}/js/common.js"></script>
<script type="text/javascript" src="{$context_root}/js/scriptaculous.js"></script>
<!-- 로그인 -->
<script language="JavaScript" type="text/JavaScript" src="{$context_root}/js/login.js"></script>
<!-- 메신저 -->
<script language="JavaScript" type="text/JavaScript" src="{$context_root}/js/messenger.js.php"></script>
<!-- 페이지마다 필요한 자바스크립트 -->
{insert_js files=$import_js}
{$inline_javascript}
</head>
<body class="{$layout_style}">
<div id="container">
<div id="pageHead">
	<div id="jaramLogo"><h1><a href="{$context_root}/">Jaram.org</a></h1></div>
	<div id="mainMenu">
		<ul id="topNav">
		<li><a href="{$context_root}/jaram/introduce/" class="menutext1">Jaram</a></li>
		<li><a href="{$context_root}/memberzone/" class="menutext1">Member Zone</a></li>
		<li><a href="{$context_root}/studyzone/jaramwiki/" class="menutext1">Study Zone</a></li>
		<li><a href="{$context_root}/tools/scheduler/" class="menutext1">Tools</a></li>
		<li><a href="{$context_root}/board/bbs.php?tableID=sitelinks" class="menutext1">Site Links</a></li>
		<li><a href="{$context_root}/board/bbs.php?tableID=guestbook" class="menutext1">Guest Book</a></li>
		</ul>
	</div>
	<div id="emblem"></div>
</div>
<div id="textWrapper">
<div id="bodyPart">
    <div id="sideMenu">
		<div id="loginForm" class="side_container">
			<h2>Member Login</h2>

			{if $smarty.session.jaram_user_uid}
			<p>
			<strong>Welcome!</strong><br />
			{$smarty.session.jaram_user_name} (<strong><a href="{$context_root}/jaram/memberinfo/?gid={$smarty.session.jaram_user_uid}"  class="sub">{$smarty.session.jaram_user_id}</a></strong>)
			</p>
			<div id="loginButton">
				<a href="{$context_root}/?page=account" class="image"><img src="{$context_root}/images/button/btn_editinfo.gif" alt="정보수정"  class="floatLeft"/></a>  
				<a href="{$context_root}/member/logout.php" class="image"><img src="{$context_root}/images/button/btn_logout.gif" alt="로그아웃"  class="floatRight"/></a>
			</div>
			{else}
			
			<!-- 로그인폼 시작 -->	
			<form action="{$context_root}/member/login_check.php" method="post" name="jaram_login" id="jaram_login">
			<input type="hidden" name="url" value="{$smarty.get.url}"/>
			<label for="member_id">Member ID</label>
			<input type="text" id="member_id" name="username" class="fullSize bold"/><br/>
			<label for="password">Password</label>
			<input type="password" id="password" name="password" class="fullSize bold"/><br/>
			<input type="checkbox" name="auto_login" id="auto_login" value="1" onclick="check_autologin();" class="checkbox"/>
			<label for="auto_login" id="auto_login_label">Auto Login</label><br/>
			<div id="loginButton">
				<img src="{$context_root}/images/button/btn_join.gif" alt="가입신청" class="floatLeft"/>
				<input type="image" src="{$context_root}/images/button/btn_login.gif" name="submit" class="floatRight" alt="로그인" />
			</div>
			</form>
			<!-- 로그인폼 끝 -->
			{/if}
		</div>

		<!-- 서브 메뉴 -->
		{$jaram_sub_menu}

		<!-- 메신저 시작 -->
		
		<!-- 베너 -->
		<div id="banners" class="floatClear">
			<ul >
			<li><a href="http://jaram.org/~webteam/fresh/" class="image"><img src="{$context_root}/images/join.gif" alt="새내기 가입 신청"/></a></li>
			<li><a href="http://study.jaram.org" target="_blank" class="image"><img src="{$context_root}/images/button/btn_studywiki.gif" alt="스터디 위키"/></a></li>
			<li><a href="./board/bbs.php?tableID=freshmanboard" class="image"><img src="{$context_root}/images/button/btn_fresh.gif" alt="신입생 게시판"/></a></li>
			</ul> 
		</div>
    </div><!-- close #sideMenu -->
	{if $layout_style ne 'index'}
	<div id="pageTitle">
		<h2>{$main_menu} : {$sub_menu}</h2>
	</div>
	{/if}
	<div id="mainContent">