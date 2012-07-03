<?php
    //GAZOOPHP PATHS
    define( "GAZOOPHP_PATH", BASE_PATH . "gazoophp/" );
    define( "LIB_DIR", GAZOOPHP_PATH . "libs/" );
    define( "CONTROLLER_DIR", GAZOOPHP_PATH . "controllers/" );
    define( "PLUGIN_DIR", GAZOOPHP_PATH . "plugins/" );
    define( "OBJ_DIR", LIB_DIR . "obj/" );
	
    //USER PATHS
    define( "USER_CONTROLLER_DIR" , BASE_PATH . "controllers/" );
    define( "USER_PLUGIN_DIR", BASE_PATH . "plugins/" );
    define( "USER_TEMPLATE_DIR", BASE_PATH . "templates/" );
    
    //SMARTY
    define( "SMARTY_DIR", GAZOOPHP_PATH . "smarty/Smarty-3.1.8/libs/" );
    define( "DIR_COMPILED", GAZOOPHP_PATH . "smarty/templates_c/" );
    define( "DIR_PLUGINS", GAZOOPHP_PATH . "smarty/plugins/" );
    define( "DIR_CACHE", GAZOOPHP_PATH . "smarty/cache/" );
    define( "DIR_CONFIG", GAZOOPHP_PATH . "smarty/config/" );
    define( "DIR_TEMPLATES", USER_TEMPLATE_DIR );	
?>