<?
    session_start();
/*
*  Author : smallcloud, Hanyang Univ. at Ansan EECS, Jaram
* 
*
*/
 // 권한확인
 if (!$_SESSION['jaram_user_id'])
 {
?>
<script language="javascript/text">
    alert('접근할 수 있는 권한이 없습니다.');
    history.back();
</script>
<?
    }
//`include('./base.php');
?>
