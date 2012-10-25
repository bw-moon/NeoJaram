<?php
login_check();
//프로그램 목록 select form
$program_select = get_select(get_program_array(), "program[]", "pid", "sub_menu", "nothing", "size=\"8\" multiple=\"multiple\"");

// 그룹 select form
$group = new JaramGroup();
$group_select = get_select($group->getCommonGroups(), "gid[]", "gid", "group_name", "nothing", "size=\"4\" multiple=\"multiple\"");
?>

<form method="post" id="group" name="group" action="./?page=auth_add_act">
<table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr>
        <td width="40%" valign="top">
            <table width="100%" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td>
                    <span class="a12b">그룹</span><br/>
                    <?=$group_select?></td>
                </tr>
                <tr>
                    <td>
                    <span class="a12b">프로그램 목록</span><br/>
                    <?=$program_select?>
                    </td>
                </tr>
            </table>
        </td>
        <td width="60%" valign="top">
            <span class="a12b">권한 설정</span><br/>
            <table width="100%" class="bbshead" cellpadding="3" cellspacing="1" border="0" bgcolor="#aaaaaa">
                <tr>
                    <td bgcolor="#efefef">View</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[view]" value="1"/></td>
                    <td bgcolor="#efefef">Read</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[read]" value="1"/></td>
                </tr>
                <tr>
                    <td bgcolor="#efefef">Post</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[post]" value="1"/></td>
                    <td bgcolor="#efefef">Comment</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[comment]" value="1"/></td>
                </tr>
                <tr>
                    <td  bgcolor="#efefef">Edit</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[edit]" value="1"/></td>
                    <td bgcolor="#efefef">Delete</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[delete]" value="1"/></td>
                </tr>
                <tr>
                    <td bgcolor="#efefef">Announce</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[announce]" value="1"/></td>
                    <td bgcolor="#efefef">Vote</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[vote]" value="1"/></td>
                </tr>
                <tr>
                    <td bgcolor="#efefef">Upload</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="perm[upload]" value="1"/></td>
                    <td bgcolor="#efefef">All Privilege</td>
                    <td bgcolor="white" align="center"><input type="checkbox" name="all_privilege" value="1"/></td>
                </tr>
            </table><br/>
            
            <br/>
            <input type="submit" name="submit" value="Add Permission" class="a12b" style="height:30px;width:100%"/>
        </td>
    </tr>
</table>
</form>


