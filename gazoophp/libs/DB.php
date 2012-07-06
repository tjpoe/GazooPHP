<?php
class DB{

	private $lastQuery;
	private $result;
	private $db;
	private $dbs; 
	private $error;
	private $unique = array ( '1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z');
	private $connectionArray;
	
    public $EMPTY_DATE = "0000-00-00 00:00:00";
	
	public function __construct( $host, $user = null, $pass = null, $name = null ){
	    // if first item is an array, it is likely the dbArray from config/db.php
	    // we'll store it and make each plugin instantiate it manually (if it needs it)
	    if( is_array( $host ) ) {
	        $this->connectionArray = $host;
	    } else {
            $this->db=mysql_connect($host, $user, $pass);
            if (!$this->db){
                $this->error = mysql_error();
                die(mysql_error());
            }
            if (!mysql_select_db($name,$this->db)){
                $this->error=mysql_error($this->db);
                die(mysql_error());
            }   
        }
    }
    public function useConnection( $dbConnection ) {
        try {
            if ( empty( $this->connectionArray ) ) {
                throw new Exception( 'connectionArray not defined' );  
            } elseif ( !array_key_exists( $dbConnection, $this->connectionArray ) ) {
                throw new Exception( 'dbConnection not defined in connectionArray' );
            }
           if ( !empty( $this->dbs[$dbConnection] ) ) {
               //return stored version
               print( "using $dbConnection" );
               return $this->dbs[$dbConnection];   
           } else {
               //create and store for later
               //print( "creating $dbConnection" );
               $this->dbs[$dbConnection] = new DB( $this->connectionArray[$dbConnection]['host'], $this->connectionArray[$dbConnection]['user'], $this->connectionArray[$dbConnection]['pass'], $this->connectionArray[$dbConnection]['db'] );
               return $this->dbs[$dbConnection];
           } 
        }catch(Exception $e) {
            error_log($e->GetTraceAsString());   
        }
        
    }
	
	private function Query($query){
        $this->result=mysql_query($query,$this->db) ;
        $this->error = mysql_error($this->db);
        if ( $this->error ) {
            error_log ( "error: $this->error. In query: $query");
            try{
                throw new Exception('');
            }catch(Exception $e){
                error_log($e->GetTraceAsString());
            }
        }
        $this->lastQuery=$query;
	}
	public function doQuery( $query ) {
		$this->Query($query);
		return $this->ArrayResults();
	}

	private function ArrayResults(){
		if (!$this->result) return "Error in Query: " . $this->LastQuery() . " . Error: " . $this->error;
		if (mysql_num_rows($this->result) == 0)
			return false; //if nothing, return nothing. 
		$temp = array();
		while ($row = mysql_fetch_assoc($this->result)){
			array_push($temp,$row);	
		}	
		foreach ( $temp as $k=>$v ) {
			if ( is_array( $v ) ) {
				foreach ( $v as $k2=>$v2 ) {
						$temp[$k][$k2] = html_entity_decode( $v2, ENT_QUOTES );
				}
			} else {
				$temp[$k] = html_entity_decode( $v, ENT_QUOTES );
			}
		}
		return $temp;
	}
	public function LastQuery(){
		return $this->lastQuery;
	}
	public function getError() {
	  return $this->error;
	}
    private function buildFields( $fields ) {
        if (is_array($fields)){
			$temp = $fields;
			$fields = '';
			foreach($temp as $k=>$v) {
			    if( !is_numeric( $k ) ) {
			        $fields .= htmlentities("$k as `$v`", ENT_QUOTES, 'UTF-8', false) . ",";   
			    } else {
			        $fields .= htmlentities($v, ENT_QUOTES, 'UTF-8', false).',';
			    }
			}
			$fields = substr($fields,0,-1);//removes last comma
            return $fields;
		} elseif ( is_string( $fields ) ) {
            return $fields;   
        }
    }
    private function buildWhere( $where ) {
        if ( is_string( $where ) ) {
            return $where;
        } elseif ( is_array( $where ) ) {
            $whereString = "";
            foreach ( $where as $k => $v ) {
                if ( !empty( $whereString ) ) {
                    $whereString .= " AND ";   
                }
                if (strpos( $k, "`" ) !== false ) {
                    $whereString .= str_replace( "`", "", $k );
                } else {
                    $whereString .= "`$k`";
                }
                $whereString .= " = " ;
                if (strpos( $v, "`" ) !== false || is_numeric( $v ) ) {
                    $whereString .= str_replace( "`", "", $v );
                } else {
                    $whereString .= "'$v'";
                }
                    
            }
            return $whereString;            
        }
    }
    public function describe( $table ) {
        return $this->doQuery( "DESCRIBE $table" );   
    }
	public function Select($table,$fields = '*',$where = '',$group = '',$order = '', $limit = ''){
		if (!empty($fields))
            $fields = $this->buildFields( $fields );
		if (!empty($where))
			$where = "WHERE (" . $this->buildWhere($where) . ")";
		if (!empty($group))
			$group = "GROUP BY $group";
		if (!empty($order))
			$order = "ORDER BY $order";
		if (!empty($limit))
			$limit = "LIMIT $limit";
		
		$query = "SELECT $fields FROM $table $where $group $order $limit";
		$this->Query($query);
		return $this->ArrayResults();
	}
	public function SingleSelect($table,$fields = '*',$where = '',$group = '',$order = ''){		
        $temp = $this->Select( $table, $fields, $where, $group, $order, 1 );
		return $temp[0];	
	}
	public function SelectField( $table, $field, $where ) {
	     $temp = $this->SingleSelect( $table, $field, $where );
	     return $temp[$field];
	}
    
	public function Count($table,$where = "1=1",$cnt = "*"){
	    if ( is_array( $where ) ) {
	         $where = $this->buildWhere($where);   
	    }
		$query = "SELECT COUNT($cnt) as cnt from $table WHERE ($where)";
		$this->Query($query);
		$temp = $this->ArrayResults();
		if (!empty($temp[0]["cnt"])) {
			return (int)$temp[0]["cnt"]; //return the #, rather than an array.
		}
		return 0;
	}
	public function Insert($table,$fields){
		if (is_array($fields)){
			$temp = $fields;
			$fields = '';
			$values = '';
			foreach ($temp as $k=>$v){
				$fields .= "`$k`,";
				if ( ( $k === 'unique_id' && (($v == null) || ($v == "") ) ) || ( $v === '#unique#' ) ) {
					$v = $this->Unique( $table, $k );
				}
				if ($v == 'NOW()' || $v == 'now()' || strstr($v, '`') !== false)
					$values .= str_replace('`', '', $v).',';
				elseif ($v == NULL)
					$values .= "NULL,";
				
				else
					$values .= "'".htmlentities($v, ENT_QUOTES, 'UTF-8', false) . "',";
			}
			$fields = substr($fields,0,-1);
			$values = substr($values,0,-1);
			
			$fields = "($fields) VALUES ($values)";
			$query = "INSERT into $table $fields";
		}else{
			$query = "INSERT into $table SET $fields";
		}
		//print( $query . "\n" );
		
		$this->Query($query);
		return mysql_insert_id(); //return new id from insert 
	}
	public function InsertUpdate( $table, $fields, $update ) {
				if (is_array($fields)){
			$temp = $fields;
			$fields = '';
			$values = '';
			foreach ($temp as $k=>$v){
				$fields .= "`$k`,";
				if ( ( $k == 'unique_id' && ($v == null || $v == "" ) ) ||  $v === '#unique#'  ) {
					$v = $this->Unique( $table, $k );					
				}
				if ($v == 'NOW()' || $v == 'now()')
					$values .= "$v,";
				elseif ($v == NULL)
					$values .= "NULL,";
				else
					$values .= "'$v',";
			}
			$fields = substr($fields,0,-1);
			$values = substr($values,0,-1);
			
			$fields = "($fields) VALUES ($values)";
			$query = "INSERT into $table $fields ON DUPLICATE KEY UPDATE $update";
		}else{
			$query = "INSERT into $table SET $fields ON DUPLICATE KEY UPDATE $update";
		}
		$this->Query($query);
		return mysql_insert_id(); //return new id from insert 
	}
	public function Delete($table,$where, $limit = null ){
		if ( $limit != null ) {
			$limit = "LIMIT $limit";
		}
		$query = "DELETE from $table WHERE ($where) $limit";
		$this->Query($query);
		return mysql_affected_rows(); //returns # of rows deleted. 

	}
	public function DeleteAll( $table, $where ) { //depricated, left in for continuity
		$this->Delete( $table, $where ); 
	}
	public function Update($table,$set,$where, $limit = null){
		$temp=null;
		if (!empty($where)) {
			$where = "WHERE (" . $this->buildWhere($where) . ")";
		} else { 
			error_log( "No where clause in Update Query to $table" );
			die();
		}
		if (is_array($set)){
			foreach ($set as $k=>$v){
				if ($v == 'now()' || $v == 'NOW()')
					$temp .= "`$k` = $v,";
				elseif ( $v == NULL )
					$temp .= "`$k` = NULL,";
				elseif ( strpos($v,'`') !== false) //for updating (`col` = `col` + 1) etc.  
					$temp .= "`$k` = $v,";
				elseif ( strpos($v,'*') !== false) //for updating (`col` = `col` + 1) etc.  
					$temp .= "`$k` = ". ltrim($v, '*') . ',';
				else
					$temp .= "`$k` = '$v',";
			}
			$set = substr($temp,0,-1);
			
		}
		if ( $limit != null ) {
			$limit = "LIMIT $limit";
		}
		
		$query = "UPDATE $table SET $set $where $limit";
		$this->Query($query);
		return mysql_affected_rows(); // returns # of rows updated	
	}
	
/**
     * Creates a unique random key
     *
     * @param  $table - string
     * @param  $field - string[optional]
     * @param  $size  - string[optional]
     * 
     * @return string - unique random key
     * 
     * @author tanner@gazoophp.com 
     */
	public function Unique( $table, $field = 'unique_id', $size = 8 ) {
			do {
				for ( $i = 0 ; $i < $size ; ++ $i ) {
					$id[] = $this->unique[rand(0,sizeof($this->unique)-1)];
				}
				shuffle($id);
				$num = implode($id);
				$cnt = $this->Count($table, "$field = '$num'");
				
				//if unique number exists in DB reshuffle
				if ($cnt > 0) {
					try{
					    //TODO: figure out a better way to handle this. duplicates ARE going to happen
					    // maybe throw a counter and throw exception in there are multiple. 
						throw new Exception($cnt.' is messing up ');
					}catch(Exception $e ){
						error_log( $e->GetTraceAsString());
					}
					error_log("$num: duplicate unique_id created in creation process.");	
					unset($id); //zero out array. 
				}

			} while ($cnt > 0);
			
			return $num;
	}
	
	public function UniqueSequencial( $table, $field = 'unique_id' ) {
			$max = $this->SingleSelect( $table, "max($field) as max" ) ;
			$max = $max['max'];
			if ($max == "") //no max, means no entries, so start with -----
					return "-----";
			$max = strrev($max);
			$parts = str_split($max);
			foreach ($parts as $k=>$part) {
					
					$i = array_search( $part, $this->unique );
					
					$max[$k] = $this->unique[$i+1];
					if ($part !== '_') {
							break;
					}
			}
			$max = strrev($max);
			return $max;
	}

}
?>
