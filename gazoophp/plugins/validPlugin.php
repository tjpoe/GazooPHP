<?php
class validPlugin extends gazooPlugin {
	public $default_date_format = 'M d, Y';
	/*
	Validate an email address.
	Provide email address (raw input)
	Returns true if the email address has the email 
	address format and the domain exists.
	
	Provided by Douglas Lovell fron http://www.linuxjournal.com/article/9585
	*/
	public function validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
	      $isValid = false;
	   }
	   else
	   {
	      $domain = substr($email, $atIndex+1);
	      $local = substr($email, 0, $atIndex);
	      $localLen = strlen($local);
	      $domainLen = strlen($domain);
	      if ($localLen < 1 || $localLen > 64)
	      {
		 // local part length exceeded
		 $isValid = false;
	      }
	      else if ($domainLen < 1 || $domainLen > 255)
	      {
		 // domain part length exceeded
		 $isValid = false;
	      }
	      else if ($local[0] == '.' || $local[$localLen-1] == '.')
	      {
		 // local part starts or ends with '.'
		 $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $local))
	      {
		 // local part has two consecutive dots
		 $isValid = false;
	      }
	      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	      {
		 // character not valid in domain part
		 $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $domain))
	      {
		 // domain part has two consecutive dots
		 $isValid = false;
	      }
	      else if (!preg_match('/\\./', $domain)){
		      //Domain part doesn't have at least on dot
		      $isValid = false;
	      }
	      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
		 // character not valid in local part unless 
		 // local part is quoted
		 if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
		    $isValid = false;
		 }
	      }
	   }
	   return $isValid;
	}
	
	public function validEmailDNS($email){
		$isValid = $this->validEmail($email);
		if ($isValid){
			$atIndex = strrpos($email, "@");
			$domain = substr($email, $atIndex+1);
			if( !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}
	// This function is not quite as good as validEmail above, but still works
	public function validEmail2($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}
	public function validPhone($phone){
		$ret = false;
		$phone = $this->parsePhone($phone);
		if( strlen($phone) == 10 && is_numeric($phone) && $phone !== '0000000000'){
			$ret = true;
		}
		return $ret;
	}
	// Parses out periods, spaces, dashes, and parens
	public function parsePhone($phone){
		$parsed = str_replace('.','',str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$phone)))));
		return $parsed;
	}
	public function validAddress($address){
		$ret = true;  //Innocent until proven guilty
		$bad_chars = array('@', '.com', '.net');
		foreach($bad_chars as $b){
			if(stripos($address, $b) !== false){
				$ret = false;
			}
		}
		return $ret;
	}
	
	public function onlyNumeric($str){
		return preg_replace('/[^0-9]/', '', $str);
	}
	
	public function onlyAlphaNumeric($str) {
		return preg_replace('/[^0-9a-z ]/i', '', $str);
	}
	
	public function formatPhone($str){
		$formattedNumber = $this->onlyNumeric($str);
		switch(TRUE){
			case strlen($formattedNumber) == 10:
				return '('.substr($formattedNumber, 0, 3).')'.substr($formattedNumber,3, 3).' - '.substr($formattedNumber,7);
			break;
			case strlen($formattedNumber) == 7:
				return substr($formattedNumber, 0, 3).' - '.substr($formattedNumber, 3);
			break;
		}
	}
	
	public function formatDate($str, $format = null){
		$format = (is_Null($format)) ? $this->default_date_format : $format;
		if(is_numeric($str)){
			return $this->formatDateFromUnixTimestamp($str, $format);
		}
		if($timestamp = strtotime($str)){
			return $this->formatDateFromUnixTimestamp($timestamp, $format);
		}		
	}
	
	public function formatDateFromUnixTimestamp($timestamp, $format){
		return date($format, $timestamp);
	}	
	
    
    
    public function validateFields( $fields, $posted ) {
        $errors = array();
        foreach ( $fields as $field ) {
            if ( $field['required'] && empty( $posted[$field['name']] ) ) {
               $errors[] = $field['label'] . " is required";
            }
            if ( !empty( $field['same'] ) ) {
                if ( $posted[$field['name']] != $posted[$field['same']] ) {
                    // get label of same field
                    foreach ( $fields as $fieldLoop ) {
                        if ( $fieldLoop['name'] == $field['same'] ) {
                            $label = $fieldLoop['label'];   
                        }
                    }
                    $errors[] = $field['label'] . " does not match match $label";   
                }
            }
            if ( !empty( $field['min'] ) && strlen( $posted[$field['name']] ) < $field['min'] ) {
                $errors[] = $field['label'] . " minimum length is " . $field['min'] . " characters";
            }
            if( !empty( $field['max'] ) && strlen( $posted[$field['name']] ) > $field['max'] ) {
                $errors[] = $field['label'] . " maximum length is " . $field['max'] . " characters";   
            }
        }
        return $errors;
    }

	
	public function fixFormDay( $day ) {
		if ( $day < 10 ) {
			return "0$day";
		} else {
			return $day;
		}
	}

}
?>
