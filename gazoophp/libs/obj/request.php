<?php

class request extends baseObj {

    
    public $controller;
    public $view;
    public $args;
    
    const paramSeparator = PARAM_SEPARATOR;
    const paramValueIndicator = PARAM_VALUE_INDICATOR;
    
    public function __construct() {
        //check loaded params. 
        //parameters given passed, exclude first /
        $params = explode( "/", substr($_SERVER['REQUEST_URI'], 1)  );
        
        /*
        if ( !empty($params) ) {
            foreach ( $params as $k=>$v ) {
                $temp_params = explode( "?", $v );
                if ( !empty($temp_params) ) {
                    $i = 0;
                    foreach ( $temp_params as $kt => $vt ) {
                        $params[$k+($i++)] = $vt;
                    }
                }
            }
        }
        */

        //first param should be controller, but is possibly the paramString, so check for the separator
        if ( !empty( $params[0] ) && 
            strpos( $params[0] , self::paramValueIndicator ) === false && 
            strpos( $params[0] , "?" ) === false && 
            strpos( $params[0] , "&" ) === false ) {
            $controller_name = $params[0];
        } else {
            $controller_name = DEFAULT_CONTROLLER;
        }
        $this->controller = $controller_name;
        
        //second param should be the view, but is possibly the paramString so check
        if ( !empty( $params[1] ) && 
            strpos( $params[1], self::paramValueIndicator ) === false &&
            strpos( $params[1], "?" ) === false && 
            strpos( $params[1], "&" ) === false ) {
            $view_name = $params[1];   
        } else {
            $view_name = DEFAULT_VIEW;   
        }
        $this->view = $view_name;
        //params
        foreach ( (array) $params as $param ) {
            //TODO: further testing for this change
            if ( !empty( $param ) && strpos( $param, self::paramValueIndicator ) !== false ) {
                $argsParam = $param;
            } elseif( count( $params ) == 3 && strpos( $param, self::paramValueIndicator ) === false ) {
                //we have a /controller/view/param syntax
                $argsParam = $param;
            }
            
        }
        if ( !empty( $argsParam ) ) {
            $args = explode( self::paramSeparator, $argsParam );
            foreach ( $args as $arg ) {
                $item = explode( self::paramValueIndicator , $arg );
                $_GET[$item[0]] = !empty($item[1])?$item[1]:null;
                $this->args[$item[0]] = !empty($item[1])?$item[1]:null;
            }
        }
        
        define( "CONTROLLER", $this->controller );
        define( "VIEW", $this->view );
    }
}

?>