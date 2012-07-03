<?php
function smarty_function_html_options_state($params, &$smarty)
{
	$state_list = array('AL'=>'Alabama',
	                'AK'=>'Alaska',  
	                'AZ'=>'Arizona',  
	                'AR'=>'Arkansas',  
	                'CA'=>'California',  
	                'CO'=>'Colorado',  
	                'CT'=>'Connecticut',  
	                'DE'=>'Delaware',  
	                'DC'=>'District of Columbia',  
	                'FL'=>'Florida',  
	                'GA'=>'Georgia',  
	                'HI'=>'Hawaii',  
	                'ID'=>'Idaho',  
	                'IL'=>'Illinois',  
	                'IN'=>'Indiana',  
	                'IA'=>'Iowa',  
	                'KS'=>'Kansas',  
	                'KY'=>'Kentucky',  
	                'LA'=>'Louisiana',  
	                'ME'=>'Maine',  
	                'MD'=>'Maryland',  
	                'MA'=>'Massachusetts',  
	                'MI'=>'Michigan',  
	                'MN'=>'Minnesota',  
	                'MS'=>'Mississippi',  
	                'MO'=>'Missouri',  
	                'MT'=>'Montana',
	                'NE'=>'Nebraska',
	                'NV'=>'Nevada',
	                'NH'=>'New Hampshire',
	                'NJ'=>'New Jersey',
	                'NM'=>'New Mexico',
	                'NY'=>'New York',
	                'NC'=>'North Carolina',
	                'ND'=>'North Dakota',
	                'OH'=>'Ohio',  
	                'OK'=>'Oklahoma',  
	                'OR'=>'Oregon',  
	                'PA'=>'Pennsylvania',  
	                'RI'=>'Rhode Island',  
	                'SC'=>'South Carolina',  
	                'SD'=>'South Dakota',
	                'TN'=>'Tennessee',  
	                'TX'=>'Texas',  
	                'UT'=>'Utah',  
	                'VT'=>'Vermont',  
	                'VA'=>'Virginia',  
	                'WA'=>'Washington',  
	                'WV'=>'West Virginia',  
	                'WI'=>'Wisconsin',  
	                'WY'=>'Wyoming',
					'AB'=>'Alberta', 
					'BC'=>'British Columbia', 
					'MB'=>'Manitoba',
					'NB'=>'New Brunswick',
					'NL'=>'Newfoundland and Labrador',
					'NT'=>'Northwest Territories',
					'NS'=>'Nova Scotia',
					'NU'=>'Nunavut',
					'ON'=>'Ontario',
					'PE'=>'Prince Edward Island',
					'QC'=>'Quebec',
					'SK'=>'Saskatchewan',
					'YT'=>'Yukon');
	$html = '';
	foreach($state_list as $abr => $state){
		$html .= '<option value="'.$abr.'" ';
		if(strtolower($abr) == strtolower($params['state'])){
			$html .= ' SELECTED ';
		}
		$html .= '>'.$state.'</option>
		';
	}
	return $html;	
}
