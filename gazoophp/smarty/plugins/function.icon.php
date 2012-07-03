<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * GazooPHP {icon} function plugin
 *
 * Type:     function<br>
 * Name:     icon<br>
 * Purpose:  print out a counter value
 * @author Tanner Postert <tanner at gazoophp dot com>
 * @link http://www.gazoophp.com/docs/smarty_plugins/icon
 * @param array parameters
 * @param Smarty
 * Params array variables
 *  @param $icon string file name of icon
 *  @param $path string path to icons dir, default: /images/icons/
 *  @param $ext string exetension, defaults to .png
 *  @param $class string class of img tag
 *  @param $link string anchor link to wrap around icon
 *  @param $link_class string class of anchor tag
 * @return string|null
 */
function smarty_function_icon($params, &$smarty)
{
    extract( $params );
    
    if( empty( $path ) ) $path = "/images/icons/";
    if ( empty( $ext ) ) $ext = "png";
    
    if ( !empty( $class ) )
        $class = "class=\"$class\"";
    else 
        $class = null;
    $img = "<img $class src=\"{$path}{$icon}.{$ext}\" />";
    
    if( !empty( $link ) ) {
        if ( !empty( $link_class ) )
            $link_class = "class = \"" . $params['link_class'] . "\" ";
        else 
            $link_class = null;    
        $return = "<a $link_class href=\"$link\">$img</a>";
    } else {
        $return = $img;   
    }
    return $return;
}

/* vim: set expandtab: */

?>
