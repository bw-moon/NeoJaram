<!-- 로그인폼 시작 -->
<?
// 로그인 되어 있을 경우
if (is_login())
{
?>
<p>
<strong>Welcome!</strong><br />
<?=$_SESSION["jaram_user_name"]?>(<strong><a href="<?=WEB_ABS_PATH?>/jaram/memberinfo/?gid=<?=$_SESSION["jaram_user_uid"]?>" ><?=$_SESSION["jaram_user_id"]?></a></strong>)
</p>
<div id="loginButton">
<a href="<?=WEB_ABS_PATH?>/?page=account" class="image"><img src="<?=WEB_ABS_PATH?>/images/button/btn_editinfo.gif" alt="정보수정"  class="floatLeft"/></a>  
<a href="<?=WEB_ABS_PATH?>/member/logout.php" class="image"><img src="<?=WEB_ABS_PATH?>/images/button/btn_logout.gif" alt="로그아웃"  class="floatRight"/></a>
</div>
<?
}
// 로그인 안되어 있을 경우 (로그인 폼 보여주기)
else {
?>
<form action="<?=WEB_ABS_PATH?>/member/login_check.php" method="post" name="jaram_login" id="jaram_login">
<input type="hidden" name="url" value="<?=$_GET['url']?>"/>
<label for="member_id">Member ID</label>
<input type="text" id="member_id" name="username" class="input small fullSize"/><br/>
<label for="password">Password</label>
<input type="password" id="password" name="password" class="input small fullSize"/><br/>
<input type="checkbox" name="auto_login" id="auto_login" value="1" onclick="check_autologin();" <?=($_COOKIE[md5("jaram_auto_login")])? "checked" : ""?> class="checkbox"/>
<label for="auto_login" id="auto_login_label">Auto Login</label><br/>
<div id="loginButton">
<img src="<?=WEB_ABS_PATH?>/images/button/btn_join.gif" alt="가입신청" class="floatLeft"/>
<input type="image" src="<?=WEB_ABS_PATH?>/images/button/btn_login.gif" name="submit" class="floatRight" alt="로그인" />
</div>
</form>
<?
}
?>
<!-- 로그인폼 끝 -->