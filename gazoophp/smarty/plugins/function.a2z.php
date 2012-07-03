<?php

function smarty_function_a2z($params, &$smarty)
{

	$vars = array( "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	
	$last = ($_SERVER['PHP_SELF']);
	$data = '';
	foreach ( $vars as $k=>$v ) {
		if ( strpos( $last, "letter=" ) !== false ) {
			$pos = strpos( $last, "letter=" );
			$page = substr( $last, 0, ($pos + 7) ) . $v . substr( $last, ($pos + 8) ) ;
		} else {
			if ( strpos( $last, "=" ) !== false ) {
				$page = $last . "&letter=$v";
			} else {
				if ( substr( $last, -1, 1 ) == "/" ) {
					$page = $last . "letter=$v" ;
				} else {
					$page =  $last . "/letter=$v";
				}
			}
		}	
		$data .= "<a " . ( ( (!empty($_REQUEST['letter']) && $_REQUEST['letter'] == $v) || empty($_REQUEST['letter']) && $k == 0 )?"style=\"font-weight: bold; font-size: 16px;\" ":"" ) . "href=\"" . $page . "\">$v</a> | "; 
	}
	$data = substr( $data, 0, -3 ); //removes last pipe(|) and spaces
	
	return $data;
}

/* vim: set expandtab: */

?>
