<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     insert_css
 * Version:  1.0
 * Date:     March 6, 2007
 * Author:	 문병원 <tasyblue@gmail.com>
 * Purpose:  insert javascript
 * -------------------------------------------------------------
 */
function smarty_function_insert_css($args)
{
	$rv = "";
    foreach (explode(',', $args['files']) as $file) {
        $rv .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$file}\" />\n";
    }
	return $rv;
}

/* vim: set expandtab: */

?>
