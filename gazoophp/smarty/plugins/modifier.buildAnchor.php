<?php
function smarty_modifier_buildAnchor($string, $target = "_blank")
{
	$string = str_replace( "http://", "", $string );
	return "<a target=\"$target\" href=\"http://$string\">$string</a>";
}
/* vim: set expandtab: */

?>
