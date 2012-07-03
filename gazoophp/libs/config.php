<?php
//TODO: build assuming 5.2+
if(!defined('__DIR__')) { 
    $iPos = strrpos(__FILE__, "/"); 
    define("__DIR__", substr(__FILE__, 0, $iPos) ); 
}
$parts = explode( "/", __DIR__ ); //in lib dir
array_pop( $parts );
array_pop( $parts );
define( "BASE_PATH", implode( "/", $parts ) . "/" );
ini_set( "include_path", ".:" . BASE_PATH . "gazoophp/libs:/usr/local/lib/php:" . BASE_PATH . "htdocs/files:" . BASE_PATH . "libs/obj" );

require_once( BASE_PATH . "config/path.php" );

require_once( BASE_PATH . "config/db.php" );

require_once( BASE_PATH . "config/url.php" );

require_once( BASE_PATH . "config/error.php" );

require_once( BASE_PATH . "config/route.php" );

require_once( BASE_PATH . "config/session.php" );

if ( !empty( $_SERVER['DEV_MODE'] ) ) {
    define( "DEV_MODE", $_SERVER['DEV_MODE'] );
} else {
    define( "DEV_MODE", "PRODUCTION" );
}    
	
?>
