<?php
class url extends baseObj {
    public $controller;
    public $view;
    public $queryString;
    
    
    public function __construct( $controller, $view, $queryString = null ) {
        $this->controller = $controller;
        $this->view = $view;
        if ( !empty( $queryString ) );
        $this->queryString = new queryString( $queryString );
    }
    public function __toString() {
        $url = BASE_URL . $this->controller . "/" . $this->view . "/" . $this->queryString;
        return $url;
    }
    
    
}
?>