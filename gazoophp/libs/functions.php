<?php
function __gazoophp_autoload($cName)
{
	if ( file_exists( USER_CONTROLLER_DIR . "$cName.php" ) ) {
	    require_once( USER_CONTROLLER_DIR . "$cName.php" );
	} elseif ( file_exists( USER_PLUGIN_DIR . "$cName.php" ) ) {
	    require_once( USER_PLUGIN_DIR . "$cName.php" );
	} elseif ( file_exists( LIB_DIR . "$cName.php" ) ) {
		require_once( LIB_DIR . "$cName.php" );
	} elseif ( file_exists( CONTROLLER_DIR . "$cName.php" ) ) {
		require_once( CONTROLLER_DIR . "$cName.php" ) ;
	} elseif ( file_exists( PLUGIN_DIR . "$cName.php" ) ) {
	    require_once( PLUGIN_DIR . "$cName.php" );
	} elseif ( file_exists( OBJ_DIR . "$cName.php" ) ) {
		require_once( OBJ_DIR . "$cName.php" ) ;
	} else {
	    /*print ( "AUTOLOAD cant find $cName in \n" . 
	        "USER_CONTROLLER_DIR:" . USER_CONTROLLER_DIR . "\n" .
	        "USER_PLUGIN_DIR:" . USER_PLUGIN_DIR . "\n" .
	        "LIB_DIR:" . LIB_DIR . "\n" .
	        "CONTROLLER_DIR:" . CONTROLLER_DIR . "\n" .
	        "PLUGIN_DIR:" . PLUGIN_DIR . "\n" .
	        "OBJ_DIR:" . OBJ_DIR . "\n" .
	        "SMARTY_DIR". SMARTY_DIR . "\n"
	        );
	        */
	}
}
spl_autoload_register( "__gazoophp_autoload", true, true );

/** Global Debug Function
    @param $var variable or content to be displayed in debug
    opens the debug object and calls the add method to push content into the debug
    */

function debug($var) {
    debug::add( $var );
}

?>
