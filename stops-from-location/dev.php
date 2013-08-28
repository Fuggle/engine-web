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
	$maxResult=5;
	$location_resolove_result_index=3;
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
    $return_array['result']=array();
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
	    
    
    
    
    echo $stops_respond;
    
    
    while (list($key, $value) = each($stops_respond)) {
    	echo "<pre> Key: $key; Value: $value</pre>";
    	while (list($key1, $value1) = each($value)) {    		
    		echo( "<pre>\t Key 1: ".$key1."=". $value1."</pre>");
    		while (list($key2, $value2) = each($value1)) {
    			echo( "<pre>\t Key 2: ".$key2."=". $value2."</pre>");
	    		if ($key2=="StopId"){	  
	    			echo("<pre>\t\t Geting stop info of stop: ".$value2."</pre>");  		
	    			$stop=new stdClass();
	    			$stop->StopId=$value2;
		    		
		    		$ds=get_stop_details($value2);
		    		$ds=json_decode($ds,true);
		    		
		    		while (list($ka, $va) = each($ds)) {
			    		echo( "<pre>\t \t Key a: ".$ka."=". $va."</pre>");
			    		while (list($kb, $vb) = each($va)) {
			    			echo( "<pre>\t \t \t Key b: ".$kb."=". $vb."</pre>");
			    				while (list($kc, $vc) = each($vb)) {
			    				echo( "<pre>\t \t \t \t Key c: ".$kc."=". $vc."</pre>");
			    				
			    				if	($kc=='Routes'){
				    				
			    				}
			    				if ($kc=="Description"){
				    				$stop->$kc=$vc;
			    				}
			    				
			    				
			    				while (list($kd, $vd) = each($vc)) {
			    					
			    					if ($kd=='Lat'||$kd=='Lng'){
			    						$stop->$kd=$vd;
			    					}
			    					
			    					echo( "<pre>\t \t \t\t \t Key d: ".$kd."=". $vd."</pre>");
			    					while (list($ke, $ve) = each($vd)) {
			    						echo( "<pre>\t \t \t \t\t\t Key e: ".$ke."=". $ve."</pre>");
			    					}
			    				}
			    			}
			    			
			    		}
		    		}
		    	
	    		} 
	    		
	    	}array_push($return_array['result'],"stop",$stop);
    	}
    }
    
    $final_result= (json_encode($return_array));
    echo $final_result;
    return($final_result);
?>