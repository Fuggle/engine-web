<?php 
	/*
		this fucnction will take in a lat and lon and return the stops around that locations,
		within the given radius.

		query to: /stops-from-latlon/?lat=-27.498137&lon=153.021022&radius=300

		provide lat, lon, radius
	*/
	
	include("../cache/cache_functions.php");

	$latitude=$_GET['lat'];
	$longitude=$_GET['lon'];
	$radius=$_GET['radius'];

	//convert the string to OPIA standard
	$id=convert_strings("GP:".$latitude.",".$longitude);
	
	$stops_response = querry_opia(("location/rest/stops-nearby/".$id."?radiusM=".$radius."&useWalkingDistance=true&maxResults=30&api_key=special-key"));
	$stops_response = json_decode($stops_response,true);

	$result_array = array();

    while (list($key, $value) = each($stops_response)) {
    	while (list($key1, $value1) = each($value)) {    		
    		while (list($key2, $value2) = each($value1)) {
	    		if ($key2=="StopId"){	  
	    			$stop=new stdClass();
	    			$stop->StopId=$value2;
		    		//print_r("val2: ".$value2." -- key2: ".$key2."<br />");
		    		$ds=get_stop_details($value2);
		    		$ds=json_decode($ds,true);
		    		//print_r($ds);
		    		while (list($ka, $va) = each($ds)) {
			    		while (list($kb, $vb) = each($va)) {
			    			while (list($kc, $vc) = each($vb)) {
			    				if($kc=="Description"){
				    				$stop->$kc=$vc;
			    				}
			    				if ($kc=="ServiceType"){
			    					$stop->$kc=$vc;
			    				}
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
	    	array_push($result_array,'stop',$stop);
    	}
    }

    $final_result= (json_encode($result_array));
    $final_result= str_replace('"stop",', '', $final_result);
	echo $final_result;
	return $final_result;
?>