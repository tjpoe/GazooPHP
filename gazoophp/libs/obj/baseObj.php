<?php
class baseObj {
    // required to error_log a child object
    private $vars;
    
    public function __toString() {
        foreach ( $this->vars as $k=>$v ) {
            $return .= "$k => $v\n" ;
        }
        return $return;
    }
}
?>