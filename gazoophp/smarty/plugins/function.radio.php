<?php

function smarty_function_radio($params, &$smarty)
{
	include_once( BASE_PATH . "libs/config.php" );
	$db = new DB();
	$controller = new baseController( $db );
	$radio = $controller->plugin( "radio", "get", array( "color" => $params['color'], "type" => $params['type'] ) );
	return "<p style=\"margin:0; padding: 0; font-weight: bold; color: black; font-size: 14px;\">Expert " . ( ( $params['type'] == 'gas' )?"Car":"Food") . " Tips RADIO</p><br />
<!-- radio plugin begin -->" .
$radio['text'] 
."<!-- radio plugin end --><br /><br />";
    
}


?>
