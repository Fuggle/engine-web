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
	
	echo $location_string;
	$location_string=str_replace(" ","+",$location_string);
	//die();
	$result=querry_opia(("location/rest/resolve?input=".$location_string."&filter=".$filter."&maxResults=".$maxResult)));
	// resolve location using translink opia
	
	
?>