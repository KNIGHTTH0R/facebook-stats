<?php

/* Example:
 
     $info_array = array(
	  "age_max" => 18,
	  "country" => array("RO"=>"Romania"),
	 ); 
 
	 $stats = new facebook_statistics($info_array);
	 print_r($stats->payload['UserCount']); 
	 
 */

include "facebook.class.php";

/* quick example */

$info_array =  array(
	  "country" => array("RO"=>"Romania"), // [COUNTRY_CODE]=>COUNTRY_NAME
	  "age_min" => 0, // 0 - 99
	  "age_max" => 0, // 0 - 99
	  //"education" => "all",
);
 
	$stats = new facebook_statistics($info_array);
	$users = $stats->payload['UserCount']; 
	// delete the collon
	$users = str_replace(",", "", "$users"); 

echo $users;