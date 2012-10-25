<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     insert_js
 * Version:  1.0
 * Date:     March 6, 2007
 * Author:	 문병원 <tasyblue@gmail.com>
 * Purpose:  insert javascript
 * -------------------------------------------------------------
 */
function smarty_function_insert_js($args)
{
	$rv = "";
    foreach (explode(',', $args['files']) as $file) {
        if (!empty($file)) {
            $rv .= "<script type=\"text/javascript\" src=\"$file\"></script>\n";
        }
    }
	return $rv;
}

/* vim: set expandtab: */

?>
