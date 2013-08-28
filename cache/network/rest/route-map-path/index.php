<?php
	//expects route and type. Eg: ?route=109&type=2

	// this page take in the route code parameter and return route map paths
	//https://opia.api.translink.com.au/v1/network/rest/route-map-path?routeCode=109&vehicleType=2&date=24+aug+2013&api_key=special-key
	include('../../../cache_functions.php');
	date_default_timezone_set('Australia/Queensland');
	
	$routecode=$_GET['route'];
	$type=$_GET['type'];
	$today= date('j+M+Y');
	$weekday=date('D');
	$bypassCahe=false;
	echo cache_engine(("network/rest/route-map-path?routeCode=".$routecode."&vehicleType=".$type."&date=".$today."&api_key=special-key"),("route_map_path_".$routecode.  "_timestable_on_".$weekday));
?>