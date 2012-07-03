<?php
class emailController extends gazooController {
    
	private function send( $message, $subject, $from, $to ) {
//		error_log( "from = $from" );
		$headers = "From: $from <$from>\r\n";
		$headers .= "X-Mailer: PHP/" . PRODUCT_NAME . PRODUCT_VERSION . "\r\n";
		//TODO: DEFINE PRODUCT_NAME AND PRODUCT_VERSION on a define somewhere
		$headers .= "X-Priority: 1\r\n";
		if( stripos( SHORT_URL, 'dev.') === 0){
			// Add an email address here if you wanna test the BCC
			// This also ensures no addresses below get mailed if its a dev site
			// $headers .= "Bcc: dev <account@email.com>\r\n";
		}
		mail($to,$subject,$message,$headers);
	}
	
}
?>
