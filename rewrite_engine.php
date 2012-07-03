<?php
require_once("gazoophp/libs/config.php");
require_once("gazoophp/libs/functions.php");

$request = new request();

$controller_name = $request->controller;
$view_name = $request->view;
$args = $request->args;

if ( DEV_MODE == 'dev' ) {
    if ( $controller_name != 'load' ) {
        error_log( "=== Routing: Controller => $controller_name\t|\tView => $view_name. \t|\tURI: " . $_SERVER['REQUEST_URI'] . " ===");
    }
}

if ( defined( 'DB_USER' ) ) {
    $db = new DB();
} elseif( !empty( $dbArray ) ) {
    $db = new DB( $dbArray );   
} else {
    $db = null;   
}

if ( class_exists( $controller_name . "Controller" ) ) {
	$controller = $controller_name . "Controller";
	$controller = new $controller($db); 
} else {
	$controller = new baseController($db);
}
$controller->__route( $controller_name, $view_name, $args );
?>
