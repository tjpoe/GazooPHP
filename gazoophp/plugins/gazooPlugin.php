<?php
require_once( "config/db.php");
abstract class gazooPlugin {
	private $namespace;
	public $table;
	protected $db;
	private $queryObject;
    public $getAll;
    public $get;
    
    //This must be set for the child plugin in order to connect using one of the $dbArray indexs
    public $dbConnectionName;
    
	public function __construct($db) {
        if( !empty( $this->dbConnectionName ) ) {
            $this->db = $db->useConnection( $this->dbConnectionName );
        } else {
            $this->db = $db;
        }   
		$name = get_class( $this );
		$this->namespace = substr( $name, 0, strpos( $name, "Plugin" ) );
		if( empty( $this->table ) ) {
		     $this->table = $this->namespace;   
		}
	}
	public function get( $id ) {
        $result = $this->db->SingleSelect( $this->table, "*", "id = '$id'" );
        return $result;
	}
	public function getAll( $order = null ) { //prevents multiple queries to getAll from executing DB code
        $result = $this->db->Select( $this->table, "*", null, null, $order );
        return $result;
	}
    
    public function find( $fields, $conditions ) {
        return $this->db->Select( $this->table, $fields, $conditions );
    }
    
    
	public function plugin( ) {
		$params = func_get_args();
		$params = array_reverse( $params ); // reorders back to front
		$plugin = array_pop( $params ); //pops first (now last) element off, which is plugin name.
		$function = array_pop( $params ); //pops next element off whic his function name
		$params = array_reverse( $params ); // re-reverses elements back to normal order
		$paramString = "";
		if ( !empty( $params ) ) {
			if ( is_array( $params ) ) {
				foreach ( $params as $k=>$v ) {
					$paramString .= "\$params[$k], "; //creates parameter string to pass to function below.
				}
				$paramString = substr( $paramString, 0, -2 ); //removes last comma(,).
			}
		}
		if ( file_exists( PLUGIN_DIR . $plugin . "Plugin.php" ) ) {
			require_once( PLUGIN_DIR . $plugin . "Plugin.php" );
		} else {
			error_log ( "$plugin not found" );
			print "$plugin not found";
			return;
		}


		$objectName = $plugin . "Plugin"; 
		$object = new $objectName($this->db);
		if ( method_exists( $object, $function ) ) {
			eval("\$result = \$object->$function($paramString);");
			//$result = eval( $object->$function($paramString);
			return $result;
		} else {
			error_log( "$plugin -> $function not found " );
			print( "$plugin -> $function not found " );
			return;
		}					
	}
	public function scrub( $string )
	{			
		if(is_array($string)){
			foreach($string as $key => $data){
				$string[$key] = $this->scrub($data);
			}
			return $string;
		}
		
		if ( !is_string( $string ) )
			return $string;
		
		return str_replace('`', '', trim(htmlentities(strip_tags(stripslashes($string)), ENT_QUOTES, "UTF-8", false)));
	}

	public function scrubWithOptions($str, $strip_tags = true, $stripslashes = true, $entities = true, $trim = true){
		if(is_array($str)){
			foreach($str as $key => $data){
				$str[$key] = $this->scrubWithOptions($data, $strip_tags, $stripslashes, $entities, $trim);
			}
			return $str;
		}
		if(!is_string($str)){
			return $str;
		}
		$str = ($strip_tags === true) ? strip_tags($str) : $str;
		$str = ($stripslashes === true) ? stripslashes($str) : $str;
		$str = ($entities === true) ? htmlentities($str, ENT_QUOTES, "UTF-8", false) : $str;
		$str = ($trim === true) ? trim($str) : $str;
		$str = str_replace('`', '', $str);
		return $str;
	}
}

?>
