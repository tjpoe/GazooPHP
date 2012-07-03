<?php
/* This file is designed to be the base controller for your deployment. You 
should add any code that is specific to your deployment here.
*/

//TODO: The only reason this isn't abstract is the need to basic functionality w/o a 
// controller (the call in rewrite controller for non-controller)
// Should look for a better way.
//abstract class baseController extends gazooController {
class baseController extends gazooController {
    public function __pre() {
    }

    public function __post() {
        $this->expose( "_TITLE", $_SERVER['SERVER_NAME'] . " : " . $this->controller . " => " . $this->method ); 
        
    }
}
?>
