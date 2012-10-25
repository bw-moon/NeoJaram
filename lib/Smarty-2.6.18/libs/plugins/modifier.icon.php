<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     icon<br>
 * Date:     Mar 23, 2007
 * Purpose:  convert icon name to img tag
 * Input:<br>
 *         - contents = icon name to show
 * Example:  {$icon_name|icon}
 * @version  1.0
 * @author   Byeong Weon Moon <tasyblue at gmail dot com>
 * @param string
 * @return string
 */
function smarty_modifier_icon($string)
{
    return "<img src=\"".WEB_ABS_PATH."/images/icons/{$string}.gif\" alt=\"{$string}\" />";    
}

/* vim: set expandtab: */

?>
