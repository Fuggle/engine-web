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
	
	//echo $location_string;
	$location_string=str_replace(" ","+",$location_string);
	//die();
	$result=querry_opia(("location/rest/resolve?input=".$location_string."&filter=".$filter."&maxResults=".$maxResult));
	//echo $result;
	// resolve location using translink opia\
	$result_array=json_decode($result,true);
	
	while (list($key, $value) = each($result_array)) {
    	echo "\tKey: $key; Value: $value<br />\n";
    	while (list($key1, $value1) = each($value)) {
    		echo( "\t\tKey: $key1; Value: $value1<br />\n");
    		while (list($key2, $value2) = each($value1)) {
    			echo("\t\t\tKey: $key2; Value: $value2<br />\n");
    		}
    	}
    }
?>