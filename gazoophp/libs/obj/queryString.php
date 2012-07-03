<?php
class queryString extends baseObj {
    public $string; //actual string
    public function __construct( $queryString ) {
        if ( is_array( $queryString ) ) { //convert to string;
            foreach ( $queryString as $k => $v ) {
                if ( !empty( $this->string ) ) {
                    $this->string .= "&";   
                }
                $this->string .= "$k=$v";
            }
        } elseif ( is_string( $queryString ) ) {
            $this->string = $queryString;   
        }
    }
    public function __toString() {
        return (String) $this->string;   
    }
}
?>