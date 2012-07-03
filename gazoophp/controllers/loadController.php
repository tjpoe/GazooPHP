<?php

class loadController extends gazooController {
    public function css() {
        //TODO: see if there is a "has smarty tags" function in the smarty classes. 
        // if so, i should be able to pull back the css/js, check if it has smarty tags in it, 
        // if so, throw them thru the display or render method to resolve them, and 
        // them echo out the content, otherwise just echo the content. 
        // This will prevent me from having to embed {literal} tags on all none-smarty based
        // javascript and css files.
        $file = BASE_PATH . "htdocs/css/auto/" . $_REQUEST['controller'] . "_" . $_REQUEST['view'] . ".css";
        if ( file_exists( $file ) ) {
            $this->cssHeaders();
            $this->display( $file );
        } else {
            $this->expose( "requestedController", $_REQUEST['controller'] );
            $this->expose( "requestedView", $_REQUEST['view'] );
            $this->display( "404.tpl" );   
        }
        exit();
    }
    public function cssAll() {
        
    }
    public function js() {
        $file = BASE_PATH . "htdocs/js/auto/" . $_REQUEST['controller'] . "_" . $_REQUEST['view'] . ".js";
        if ( file_exists( $file ) ) {
            $this->jsHeaders();
            $this->display( $file );
        } else {
            $this->expose( "requestedController", $_REQUEST['controller'] );
            $this->expose( "requestedView", $_REQUEST['view'] );
            $this->display( "404.tpl" );   
        }
        exit();
    }
    public function jsAll() {
        
    }
}

?>
