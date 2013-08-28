<?php 
	/*
		this fucnction will take in a location name and return the stops arround that locations,
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

	$location_string=$_GET['location'];
	$filter=0;
	$maxResult=4;
	$location_resolove_result_index=1;
	//convert the string to OPIA standard
	$location_string=convert_strings($location_string);
	//die();
	//re
	$result=querry_opia(("location/rest/resolve?input=".$location_string."&filter=".$filter."&maxResults=".$maxResult));
	//echo $result;
	$result_array=json_decode($result,true);
	
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
    $single_location_id=$result_array["Locations"][$location_resolove_result_index]['Id'];
    //echo ("<p>".$single_location_id."</p>");
    
    //convert the string to OPIA standards
    $single_location_id=convert_strings($single_location_id);
    //echo('<br>');
    //echo $single_location_id;
    
    $radiusM=1000;
    $maxResults=30;
    $stops_respond=get_stops_from_location($single_location_id,$radiusM,$maxResults);
    //convert the respond into associate array
    $stops_respond=json_decode($stops_respond,true);
    
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
    echo $final_result;
    return($final_result);
?>