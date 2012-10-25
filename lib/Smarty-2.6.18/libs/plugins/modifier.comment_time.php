<?php
function smarty_modifier_comment_time($string)
{
    $time_str = "";
    if ($string < 60*60) {
        $time_str = (int)($string / (60)) ." minutes ago";
    }
    else if ($string < 60*60*24) {
        $time_str = (int)($string / (60*60)) ." hours ago";
    } else {
        $time_str = (int)($string / (60*60*24)). " days ago";
    }
    return $time_str;
}
?>
