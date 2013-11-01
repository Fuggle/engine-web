<?php
	//expects route and type. Eg: ?route=109&type=2

	// this page take in the route code parameter and return route map paths
	//https://opia.api.translink.com.au/v1/network/rest/route-map-path?routeCode=109&vehicleType=2&date=24+aug+2013&api_key=special-key
	include('../cache_functions.php');
	$input=$_GET['input'];
	$bypassCahe=false;
	echo querry_opia(($input));
?>