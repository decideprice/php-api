<?php

// Dont Edit the Code below.
if (!defined('DP_MAIN_URL')) {define('DP_MAIN_URL', 'http://api.decideprice.com/theme/');}

class decideprice_theme{

	function __construct() {        
    }

    function get($request){

    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,DP_MAIN_URL.$request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$headers = array();
		$headers[] = 'Authorization:'.DP_API_KEY;

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

		return json_decode($server_output,true);

    }

}