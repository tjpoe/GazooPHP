<?php
//TODO: this seems broken since updating to PHP 5.3 & Smarty 3. need to fix the missing index problems.
class debug extends baseObj {
    private static $debugVars; //items to debug;
    
    public static function add( $var ) {
        if ( DEV_MODE !== "dev" ) {
            exit;   
        }
        $backtrace = debug_backtrace();
        if ( is_array( $var ) ) {
            error_log( print_r($var, true ) . "@ " . $backtrace[1]['line'] );
        } else {
            error_log( $var  . "@ " . $backtrace[1]['line'] );
        }
 
        $debugContent['content'] = $var;
        $debugContent['line'] = $backtrace[1]['line'];
        $debugContent['full_file'] = $backtrace[1]['file'];
        $debugContent['file'] = self::simplify( $backtrace[1]['file'] );
        foreach ( $backtrace as $k => &$v ) {
            $v['full_file'] = $v['file'];
            $v['file'] = self::simplify( $v['file'] );
            if ( !empty( $v['class'] ) ) {
                $v['full_class'] = $v['class'];
                $v['class'] = self::simplify( $v['class'] );
            }
            $debugContent['backtrace'][] = $v;
        }
        $debugContent['backtraceString'] = $printbacktrace;
        self::$debugVars[] = $debugContent;
    }
    public static function get() {
        return self::$debugVars;   
    }
    public function simplify( $file ) {
        return  str_replace( array ( BASE_PATH, "libs/", "/controllers/", "Controller.php", "plugin.php", "plugins/" ), array ( "", "l/" , "/c/", "", "", "p/" ) ,  $file ) ; 
    }

}

?>