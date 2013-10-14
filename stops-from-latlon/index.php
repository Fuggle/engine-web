<?php 
	/*
		this fucnction will take in a lat and lon and return the stops around that locations,
		location name: String
		return a json string: 
		{result:
			[
			stop:{
				stopId:'000000',
				stopName:'Stop name',
				long:number,
				lat:number
				},
			stop:{...},
			]
		}		
	*/
	
	include("../cache/cache_functions.php");

	$latitude=$_GET['lat'];
	$longitude=$_GET['lon'];
	$radius=$_GET['radius'];
	$filter=0;
	$maxResult=4;
	$location_resolve_result_index=0;

	//convert the string to OPIA standard
	$latlon=convert_strings($latitude.",".$longitude);
	//https://opia.api.translink.com.au/v1/location/rest/resolve?input=21.00042%2C35.9021&filter=0&maxResults=4&api_key=special-key
	$result=querry_opia(("location/rest/resolve?input=".$latlon."&filter=0&maxResults=1"));
	//echo $result;
	$result_array=json_decode($result,true);
	print_r($result_array);

	/*
	while (list($key, $value) = each($result_array)) {
	    	echo "<pre> Key: $key; Value: $value</pre>";
	    	while (list($key1, $value1) = each($value)) {
	    		echo( "<pre>\t Key: ".$key1."=". $value1."</pre>");
	    		while (list($key2, $value2) = each($value1)) {
	    			echo( "<pre>\t | \t Key:".$key2."=". $value2."</pre>");
	    		}
	    	}
	    }
	*/
    $single_location_id=$result_array["Locations"][$location_resolve_result_index]['Id'];
    
    //convert the string to OPIA standards
    $single_location_id=convert_strings($single_location_id);
    //echo('<br>');
    //echo $single_location_id;
    
    $maxResults=30;
    $stops_respond=get_stops_from_location($single_location_id,$radius,$maxResults);
    //convert the respond into associate array
    $stops_respond=json_decode($stops_respond,true);
    print_r($stops_respond);
    die();

    
    $return_array= array();
    /*
	while (list($key, $value) = each($stops_respond)) {
	    	echo "<pre> Key: $key; Value: $value</pre>";
	    	while (list($key1, $value1) = each($value)) {    		
	    		echo( "<pre>\t Key 1: ".$key1."=". $value1."</pre>");
	    		while (list($key2, $value2) = each($value1)) {
	    			echo( "<pre>\t Key 2: ".$key2."=". $value2."</pre>");
		    		if ($key2=="StopId"){
		    			
		    		}
		    	}
		    }
		}	
	*/
	    
    
    
    
    
    
    while (list($key, $value) = each($stops_respond)) {
    	while (list($key1, $value1) = each($value)) {    		
    		while (list($key2, $value2) = each($value1)) {
	    		if ($key2=="StopId"){	  
	    			$stop=new stdClass();
	    			$stop->StopId=$value2;
		    		//echo ($value2 ." ".  $key2);
		    		$ds=get_stop_details($value2);
		    		$ds=json_decode($ds,true);
		    		while (list($ka, $va) = each($ds)) {
			    		while (list($kb, $vb) = each($va)) {
			    				while (list($kc, $vc) = each($vb)) {
			    				if($kc=="Description"){
				    				$stop->$kc=$vc;
			    				}else{}
			    				while (list($kd, $vd) = each($vc)) {
			    					if ($kd=='Lat'||$kd=='Lng'){
			    						$stop->$kd=$vd;
			    					}			    					
			    				}
			    			}
			    			
			    		}
		    		}
		    	
	    		} 
	    	}
	    	//$stop_contener=new stdClass();
	    	//$stop_contener->stopcontener=$stop;
	    	array_push($return_array,'stop',$stop);
    	}
    }
    
    $final_result= (json_encode($return_array));
    $final_result= str_replace('"stop",', '', $final_result) ;  
    print_r($final_result);
    return($final_result);
?>