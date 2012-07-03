<?php
	session_name( SESSION_NAME );
	session_start();
	
	require_once( SMARTY_DIR . "Smarty.class.php" );
	
	abstract class gazooController
	{	
		
		public $smarty;
		public $db;
		public $plugins = array();
                
		public $controller;
		public $method;
		public $paramString;
		
        public $renderTemplate = "";
        		
		// Constructor and support functions -----------------------------------
		public function __construct( $db, $smarty = null )
		{
			if ( $smarty == null ) {
				$this->smarty = new Smarty();
                $this->smarty->setTemplateDir( DIR_TEMPLATES );
                $this->smarty->setCompileDir( DIR_COMPILED );
                $this->smarty->setConfigDir( DIR_CONFIG );
                $this->smarty->setCacheDir( DIR_CACHE );
			} else {
				$this->smarty = $smarty;
			}	
			$this->db = $db;
								
			$_REQUEST = Array();
			// Scrub all the request variables and regenerate _REQUEST
			foreach( $_GET AS $key=>$val )
			{
				if ( is_array( $val ) ) {
					foreach ( $val as $k=>$v ) {
						// TODO: For GET vars, may want to pass through urldecode as well
						$_GET[$key][$k] = $this->scrub($v);
					}
				} else {
					// TODO: For GET vars, may want to pass through urldecode as well.
					$_GET[$key] = $this->scrub( $val );
				}
				$_REQUEST[$key] = $_GET[$key];
			}
				
			foreach( $_POST AS $key=>$val )
			{
				if ( is_array( $val ) ) {
					foreach ( $val as $k=>$v ) {
						if ( substr( $key, 0, 8 ) == "override" ){
							$_POST[$key] = $val; // security? 
							$newkey = str_replace('override', '', $key);
							$_POST[$newkey] = scrubWithOptions($val, false);
					}	else
							$_POST[$key][$k] = $this->scrub($v);
					}
				} else {
					if ( substr( $key, 0, 8 ) == "override" ){
						$_POST[$key] = $val; // security? 
						$newkey = str_replace('override', '', $key);
						$_POST[$newkey] = $val;						
					}else 
						$_POST[$key] = $this->scrub( $val );
				}
				$_REQUEST[$key] = $_POST[$key];		
				$newkey = str_replace('override', '', $key);
				$_REQUEST[$newkey]	= $_POST[$key];
			}				 			

            $this->template = CONTROLLER . "/" . VIEW ;
            
			$this->assignCommon();			
		}
		        
		public function smartyTemplate(){
			$tpl = new Smarty();
			$tpl->template_dir = DIR_TEMPLATES;
			$tpl->compile_dir = DIR_COMPILED;
			$tpl->plugins_dir[] = DIR_PLUGINS;
			return $tpl;
		}
		
        public function __pre() {
            
        }
        public function __post() {
            
        }
		
        // route to method with parameters
        public function __route( $controller, $method, $params = null) {
            $this->controller = $controller;
            $this->method = $method;
            
            $this->__pre();
            if ( method_exists( $this, $method ) ) {
                if( !empty( $params ) ) {
                    $paramValues = array_values( $params );
                }
                    
                if( is_array( $params) && count( $params == 1 ) && $paramValues[0] == null ) {
                    //we have a "/controller/method/param/" syntax, pass the 3rd param as the value
                    $paramValue = array_keys( $params );
                    call_user_func_array( array( $this, $method ), $paramValue );
                } else {
                    call_user_func_array( array( $this, $method ), (array) $params );
                }
            }
            $this->__post();
            $this->__render();
            $this->__debug();            
        }
               
        public function __render() {
            if ( !empty( $this->renderTemplate ) && file_exists( DIR_TEMPLATES . $this->renderTemplate . ".tpl" ) ) {
                $this->display(DIR_TEMPLATES . $this->renderTemplate . ".tpl");
            } elseif ( file_exists( DIR_TEMPLATES . $this->controller . "/" . $this->method . ".tpl" ) ) {
                $this->display(DIR_TEMPLATES . $this->controller . "/" . $this->method . ".tpl");
            } else {
                // if the template being called is doWhatever and doWhatever doesn't exist and 
                // whatever template exists, use it.
                if ( substr( $this->method, 0, 2 ) == "do" && substr( $this->method, 2, 1 ) == strtoupper( substr( $this->method, 2, 1 ) ) ) {
                    $orig_template = strtolower( substr( $this->method, 2, 1 ) ) . substr( $this->method, 3 );
                    if ( file_exists( DIR_TEMPLATES . $this->controller . "/" . $orig_template . ".tpl" ) ) {  
                        $this->display(DIR_TEMPLATES . $this->controller . "/" . $orig_template . ".tpl");
                    } else {
                        $this->NotFoundHeaders();
                        $this->display( DIR_TEMPLATES . "404.tpl");
                    }
                } else {
                    $this->NotFoundHeaders();
                    $this->display( DIR_TEMPLATES . "404.tpl");
                } 
            }
   
        }
        public function __debug() {
            if( defined( "DEV_MODE" ) && DEV_MODE === "dev" && $debug = debug::get() ) { //only show
                $this->expose( "debug", $debug );
                $this->display( DIR_TEMPLATES . "debug.tpl" );
            }
   
        }
        
		public function execute( $controller_name, $function, $variable = null ) {
			if ( class_exists( $controller_name . "Controller" ) ) { // controller exists, check for method
				
				$controller = $controller_name . "Controller";
				$controller = new $controller($this->db, $this->smarty); 
				if ( method_exists( $controller, $function ) ) { // method exists, execute it
					return $controller->$function( $variable );	
				} else {
					die( "function $function does not exist in $controller_name");
				}
			} else {
				die( "Controller $controller_name does not exist" );
			}
		}
		public function display($display) {
			$this->smarty->display($display);
		}		

		private function assignCommon()
		{
			$this->smarty->assign( "_THIS", $_SERVER["PHP_SELF"] );
			$this->smarty->assign( "dmo", $this );
			if ( !empty($_SESSION['user'] ) ){
				$this->smarty->assign( "_USER", $_SESSION['user'] );
			}
			if ( !empty($_SESSION) ) {
				$this->smarty->assign( "_SESSION", $_SESSION );
			}
			
			// Expose all defined variables (prefix with underscore)
			$defines = get_defined_constants(true);
			$defines = $defines["user"];

			foreach( $defines AS $key=>$val )
				$this->smarty->assign( "_$key", $val );
				
			$this->smarty->assign( "_REQUEST", $_REQUEST );
		}		
		
		public function expose( $name, $value = false) {
		    if( is_array( $name ) ) { //if the first item is an array, cycle thru them and expose all
		        foreach( $name as $key => $val ) {
		            $this->expose( $key, $val );
		        }
		    }
			if ( $value !== false ) 	{				
				if ( is_string( $value ) ) {
						$this->smarty->assign( $name,  html_entity_decode( $value, ENT_QUOTES ) );
				} else {
					$this->smarty->assign( $name, $value );
				}
			}
		}

//		public function plugin( $plugin, $function, $params = null ) {
		public function plugin() {
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
			if ( empty( $this->plugins[$plugin] ) ) {
				//print($paramString);
				//error_log( "PLUGIN CALL: $plugin - $function" );
				require_once( PLUGIN_DIR . "gazooPlugin.php" );
				if ( file_exists( USER_PLUGIN_DIR . $plugin . "Plugin.php" ) ) {
					require_once( USER_PLUGIN_DIR . $plugin . "Plugin.php" );
				} elseif( file_exists( PLUGIN_DIR . $plugin . "Plugin.php" ) ) { 
				    require_once( PLUGIN_DIR . $plugin . "Plugin.php" );
				} else {
					error_log ( "$plugin plugin not found in " . PLUGIN_DIR . $plugin . "Plugin.php" );
					print "$plugin plugin not found" . PLUGIN_DIR . $plugin . "Plugin.php";
					return;
				}
				$objectName = $plugin . "Plugin"; 
				$object = new $objectName($this->db);
				$this->plugins[$plugin] = $object;
			} else {
				$object = $this->plugins[$plugin];
			}		
			
			if ( method_exists( $object, $function ) ) {
                error_log( "===== Plugin Call: plugin => $plugin \t|\tmethod => $function. \t|\tparams: " . implode( ",", $params ) . " ===");
				eval("\$result = \$object->$function($paramString);");
				//$result = eval( $object->$function($paramString);
				return $result;
			} else {
				error_log( "$plugin -> $function not found " );
				print( "$plugin -> $function not found " );
				return;
			}
		}	


		
		// Support Functions ---------------------------------------------------
        public function NotFoundHeaders() {
            header("HTTP/1.0 404 Not Found");
        }
		public function xmlHeaders() {
				header("Content-Type: text/xml");
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				header('Cache-Control: no-store, no-cache, must-revalidate');
				header('Cache-Control: post-check=0, pre-check=0', FALSE);
				header('Pragma: no-cache');
		}
        public function cssHeaders() {
           header("Content-type: text/css");
           header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
        }
        public function jsHeaders() {
            header("Content-type: application/x-javascript");
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
        }
		public function csvHeaders($file="file.csv") {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$file\"");
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
			
			return str_replace('`', '', trim(htmlentities(strip_tags(stripslashes($string)), ENT_QUOTES, "UTF-8")));
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
		
		public function redirectTo( $controller = DEFAULT_CONTROLLER , $view = DEFAULT_VIEW , $query = null)
		{
			$q = null;
			if ( $query ) {
				foreach ( $query as $k=>$v ) {
					if ( $v != null ) {
						$q .= "$k=$v&";
					} else {
						$q .= "$k&";
					}
                    //TODO: allow arrays
				}
				$q = substr($q, 0, strlen($q)-1 ); //removes trailing &
			}
            //before we redirect we need to check for debug and save it for later. 
           
            
			header("Location: " . BASE_URL . $controller . "/" . $view . "/" . $q );
			exit();
		}
		public function redirectToURL( $url ) {
			header("Location: " . $url );
			exit();
		}
		
		public function exposePaginationHTML($result_name, paginationPlugin $obj) {
			//creating urls for next link
			$this->expose("next_url", $obj->getURL('next'));
			$this->expose("previous_url", $obj->getURL('previous'));
			
			//creating urls for page numbers
			$this->expose("page_number_urls", $obj->getNumberURLS());
			
			//getting urls for goto page number <select>
			$this->expose("page_num_select", $obj->getNumSelect());
			
			//displaying page range
			$this->expose("page_first", $obj->getPageFirst());
			$this->expose("page_last", $obj->getPageLast());
			
			$this->expose("total_orders", $obj->getTotal()); //displaying total
			
			$bool = ($obj->pagination === false ? 'false' : 'true');

			$this->expose($result_name, $obj->getResults());
			$this->expose('pagination', $bool);			
		}
        public function setErrorAndRedirect( $error, $controller, $view, $params = null ) {
            $this->setErrorsAndRedirect( array( $error ), $controller, $view, $params );
        }
        public function setErrorsAndRedirect( $errors, $controller, $view, $params = null) {
            $_SESSION['errors'] = $errors;
            $this->redirectTo( $controller, $view, $params );
        }
        public function clearErrors() {
            $_SESSION['errors'] = array();   
        }
        
        public function __get( $name ) { 
            if ( !empty( $this->plugins[$name] ) ) 
                return $this->plugins[$name];
            
            require_once( USER_PLUGIN_DIR . "basePlugin.php" );
            if ( file_exists( USER_PLUGIN_DIR . $name . "Plugin.php" ) ) {
                require_once( USER_PLUGIN_DIR . $name . "Plugin.php" );
                $objectName = $name . "Plugin"; 
				$object = new $objectName($this->db);
				$this->plugins[$name] = $object;
                return $object;
            } else {
                //TODO: Proper Error Logging
                error_log ( "$name plugin not found" );
                print "$name plugin not found" . PLUGIN_DIR . $name . "Plugin.php";
                return;
            }
            
            
            
        }

	}
?>
