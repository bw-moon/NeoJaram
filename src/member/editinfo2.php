<?php
include_once realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php";

login_check();

$user = new JaramUser($_SESSION['jaram_user_uid']);

// 비밀번호를 바꿀 경우
if (!empty($_POST["pass"]) && !empty($_POST["re_pass"])) {
	// 비밀번호와 비밀번호 확인 란이 다를 경우
	if ($_POST["pass"] != $_POST["re_pass"]) {
		show_error_msg("새로운 비밀번호와 확인이 일치하지 않습니다.", "back");
		exit;
	} else {
		$user->setPassword($_POST['old_pass'], $_POST['pass']);
	}
}

$user->setBirthday($_POST['birthday']);
$user->setEmail($_POST['email']);
$user->setHomepage($_POST['homepage']);
$user->setCellPhone($_POST['phone1']);
$user->setMessenger($_POST['msgr_type'], $_POST['msgr_id']);
$user->setSign($_POST['sign']);

// upload profile
if (is_uploaded_file($HTTP_POST_FILES['profileMain']['tmp_name']))
{
	$newName=$homepage_path."/member/profile/".$_SESSION["jaram_user_uid"];
	if(move_uploaded_file($HTTP_POST_FILES['profileMain']['tmp_name'], $newName))
	{
		$tempFileBaseName=explode(".",$HTTP_POST_FILES['profileMain']['name']);
		$tempFileBaseNameLocate=sizeof($tempFileBaseName)-1;
		copy_resize_img($newName,$newName."_resize", 100,100, $tempFileBaseName[$tempFileBaseNameLocate]);
		$query .= ", user_having_image1= 'true' ";
	}
}

// upload profile
if (is_uploaded_file($HTTP_POST_FILES['profileSub']['tmp_name']))
{
	$newName=$homepage_path."/member/profile/".$_SESSION["jaram_user_uid"]."_sub";
	if(move_uploaded_file($HTTP_POST_FILES['profileSub']['tmp_name'], $newName))
	{
		$tempFileBaseName=explode(".",$HTTP_POST_FILES['profileSub']['name']);
		$tempFileBaseNameLocate=sizeof($tempFileBaseName)-1;
		copy_resize_img($newName,$newName."_resize", 40,40, $tempFileBaseName[$tempFileBaseNameLocate]);
		$query .= ", user_having_image2= 'true' ";
	}
}

if ($user->saveUser()) {
	echo "<script> alert('수정되었습니다!'); </script>\n";
	p_redirect(WEB_ABS_PATH."/?page=account");
} else {
	show_error_msg("에러입니다. 관리자에게 문의해주세요", "back");
	exit;
}
?>