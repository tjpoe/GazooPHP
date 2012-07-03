<?php
function smarty_function_shipping_tracking($params, &$smarty)
{
   extract( $params );
	 switch ( $company ) {
	 case "FedEx":
		 $url="<a href=\"http://www.fedex.com/Tracking?sum=n&ascend_header=1&clienttype=dotcom&spnlk=spnl0&initial=n&cntry_code=us&tracknumber_list=1234&language=english&track_number_0=$number&track_number_replace_0=1234&resubmit_all=Resubmit\">$number</a>";
		 break;
	 case "DHL":
		 $url="<form id=\"form\" target=\"_blank\" method=\"post\" action=\"http://track.dhl-usa.com/TrackByNbr.asp?nav=Tracknbr\"><input type=\"hidden\" name=\"txtTrackNbrs\" value=\"$number\" /><a href=\"#\" onclick=\"$('form').submit();return false;\">$number</a></form>";
		 break;
	 case "UPS":
		 $url = "<a href=\"http://wwwapps.ups.com/WebTracking/processInputRequest?HTMLVersion=5.0&loc=en_US&Requester=UPSHome&tracknum=$number&ignore=&track.x=0&track.y=0\">$number</a>";
		 break;
	 case "Manual":
		 $url = "Manually";
	 }
	 return $url;
}
?>
