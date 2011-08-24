<?php

 /* Example:
 
     $info_array = array(
	  "age_max" => 18,
	  "country" => array("RO"=>"Romania"),
	 ); 
 
	 $stats = new facebook_statistics($info_array);
	 print_r($stats->payload['UserCount']); 
	 
 */

class facebook_statistics{
	 
    private $template_array = array(
	  "country" => array("RO"=>"Romania"), // [COUNTRY_CODE]=>COUNTRY_NAME
	  "age_max" => 18, // 0 - 99
	  "age_min" => 18, // 0 - 99
	  "sex" => "", // "male","female"
	  "currency" => "USD", // Dont fuck with this one ( php ninjas excluded )
	  "education" => "all", // 	"alumni", "college", "hs"
	  "interested" =>  array(), // "women","man"
	  "relations" => array() // "single","married","relationship","engaged"
	 );
	 
	function __construct($info) {
	 
	 if(!is_array($info)){ return false; }
	 
	 foreach($this->template_array as $item => $value) {
	   if(!array_key_exists($item,$info)){
	    $info[$item] = $value;
	   }
	 }
	 
     $postdata = "location_type=everywhere" . $this->f_Country($info["country"]) . "&age_min={$info['age_min']}&age_max={$info['age_max']}&sex={$info['sex']}&bid_estimate=1&currency={$info['currency']}&education={$info['education']}&fb_dtsgs=gK1-T" . $this->f_Interested($info["interested"]) . $this->f_Relationship($info["relations"]);

     $ch = curl_init();
	 
	 curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-type" => "application/x-www-form-urlencoded;charset=UTF-8"));
	 curl_setopt($ch,CURLOPT_HEADER, 0);
     curl_setopt($ch,CURLOPT_URL, "http://www.facebook.com/ajax/inventory_estimator.php?__a=1");
	 curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)"); 
     curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch,CURLOPT_POST, true);
	 curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
	 
	 $data = curl_exec($ch);
     curl_close($ch);
	 $data = str_replace("for (;;);","",$data);
	 $jsData = json_decode($data,true);
	 
	 foreach($jsData as $key => $value){
     	 $this->$key = $value;
	 }
	 
    }

    function f_Relationship($relations){
	 if(count($relations) < 1){ return ""; }
	 $relations_count = 0;
	 $relations_params = "&";
	 foreach($relations as $relation){
	   $relations_params .= "relationship[relation]=$relation";
	   $relations_count++;
	 }
	 return $relations_params;
	}
	
	function f_Interested($interests){
     if(count($interests) < 1){ return ""; }
	 $interest_count = 0;
	 $interest_params = "&";
	 foreach($interests as $interest){
	   $interest_params .= "interested_in[$interest]=$interest";
	   $interest_count++;
	 }
	 return $interest_params;
	}
	
	function f_Country($country){
	 $country_count = 0;
	 $country_params = "";
	 foreach($country as $short_name => $name){
	   $country_params .= "&countries[$country_count]=$short_name&country_names[$country_count]=$name";
	   $country_count++;
	 }
	 return $country_params;
	}
	
} 
   
