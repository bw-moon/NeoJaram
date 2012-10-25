<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     msg_form
 * Version:  1.0
 * Date:     March 20, 2007
 * Author:	 문병원 <tasyblue@gmail.com>
 * Purpose:  show msg form
 * -------------------------------------------------------------
 */
function smarty_function_msg_form($args)
{
	switch ($args['type']) {
		case "error":
			$args['type'] = "error";
			break;
		case "warn":
			$args['type'] = "exclamation";
			break;
		default:
			$args['type'] = "information";
			break;
	}

	$str = "<div id=\"jaram_one_line_msg\">
	<div class=\"msg\"><span class=\"{$args['type']}\"> {$args['msg']}</span></div>
    </div>"; 

    if ($args['msg']) {
        return $str;
    } else {
        return "";
    }
}

/* vim: set expandtab: */

?>
