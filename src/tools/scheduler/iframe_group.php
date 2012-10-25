<?
include_once(realpath('../../lib/includes')."/library.inc.php");
dbconn();
if(!empty($_GET['group_name'])){
    $query = "SELECT * FROM jaram_groups WHERE group_name LIKE '%".$_GET['group_name']."%';";
    $result = mysql_query($query) or die(mysql_error());
    $result_c = mysql_affected_rows();
    $div_s = "<select name='group_ok' style='width:400px' multiple='multiple' size='8' onchange='group_value()'>";
    while ( $row = mysql_fetch_array( $result ) ) {
            $div_s .= "<option value='".$row['gid']."'>".$row['group_name']."</option>";
    }
    $div_s .= "</select>";
}
if(0<$result_c){
	echo $div_s;
    echo "<script>
    parent.DD.innerHTML = \"$div_s\";
    </script>";
}else{
    echo "<script>
    parent.DD.innerHTML = '';
    </script>";
}
?>