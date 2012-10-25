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
 * Name:     nl2br<br>
 * Date:     Feb 26, 2003
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$text|nl2br}
 * @link http://smarty.php.net/manual/en/language.modifier.nl2br.php
 *          nl2br (Smarty online manual)
 * @version  1.0
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @modifier Styx (tasyblue@gmail.com)
 * @param string
 * @return string
 */
function smarty_modifier_nl2br($string)
{
    if (stripos($string, '<br') === false && stripos($string, '<p>') === false) {
        return nl2br($string);
    } else {
        return $string;
    }
    
}

/* vim: set expandtab: */

?>
