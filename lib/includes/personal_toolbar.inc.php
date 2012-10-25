<?php
switch ($_GET['page']) {
    case "auth_management":
        $page_title = "Authorization For: ";
        $include_file = "/member/auth_management.php";
        break;
    case "auth_add_act":
        $page_title = "Authorization Add: ";
        $include_file = "/member/auth_add_act.php";
        break;
    case "auth_add":
        $page_title = "Authorization Add: ";
        $include_file = "/member/auth_add.php";
        break;
    case "auth_modify":
        $page_title = "Authorization Modify: ";
        $include_file = "/member/auth_modify.php";
        break;
    case "auth_modify_act":
        $page_title = "Authorization Modify: ";
        $include_file = "/member/auth_modify_act.php";
        break;
    case "account":
        $page_title = "Account Infomation: ";
        $include_file = "/member/editinfo.php";
        break;
    case "custom":
        $page_title = "Configure Custom Menu For: ";
        $include_file = "/member/custom_menu.php";
        break;
    case "custom_add":
        $page_title = "Add Custom Menu: ";
        $include_file = "/member/custom_menu_save.php";
        break;
    case "bookmark_add":
        $page_title = "Add Bookmark: ";
        $include_file = "/member/bookmark_add.php";
        break;
    case "bookmark_del":
        $page_title = "Delete Bookmark: ";
        $include_file = "/member/bookmark_del.php";
        break;
    case "bookmark_edit":
        $page_title = "Edit Bookmark: ";
        $include_file = "/member/bookmark_edit.php";
        break;
    case "monitor_add":
        $page_title = "BBS Monitor Add: ";
        $include_file = "/member/monitor_add.php";
        break;
    case "monitor_del":
        $page_title = "BBS Monitor Delete: ";
        $include_file = "/member/monitor_del.php";
        break;
    case "group_list":
        $page_title = "Group List: ";
        $include_file = "/member/group_list.php";
        break;
    case "group_join":
        $page_title = "Group Join: ";
        $include_file = "/member/group_join.php";
        break;
    case "group_permit":
        $page_title = "Group Management: ";
        $include_file = "/member/group_permit.php";
        break;
    case "group_add":
        $page_title = "Add New Group: ";
        $include_file = "/member/group_add.php";
        break;
    case "sendmessage":
        $page_title = "Send A Message: ";
        $include_file = "/member/send_message.php";
        break;
    case "mailing":
        $page_title = "Send A Message: ";
        $include_file = "/member/send_mailing.php";
        break;
	case "widget":
		$page_title = "Widget Management: ";
		$include_file = "/member/widget.php";
		break;
	case "one_line":
		$page_title = "One Line Message: ";
		$include_file = "/member/one_line.php";
		break;
    default:
        $page_title = "Personal Page For: ";
        $include_file = "/member/personal_page.php";
        break;
}
?>

<h2><?=$page_title.$_SESSION['jaram_user_id']?></h2>

<p class="menu">
<a href="./?page=front">초기 화면</a> | 
<a href="./">개인화 페이지</a> | 
<a href="./?page=one_line&amp;action=list">한줄메시지</a> |
<a href="./?page=custom">커스텀 메뉴</a> | 
<a href="./?page=widget&amp;action=list">위젯 관리</a> |
<a href="./?page=group_list">그룹 관리</a> | 
<a href="./?page=account">개인 정보</a> 
<?
if (is_admin()) {
?>
| <a href="./?page=auth_management">권한 관리</a> | <a href="./?page=mailing">그룹 메일링</a> 
<?
}
?>
</p>