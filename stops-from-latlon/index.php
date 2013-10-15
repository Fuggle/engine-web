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
	
	echo cache_engine(("location/rest/stops-nearby/".$id."?radiusM=".$radius."&useWalkingDistance=true&maxResults=30&api_key=special-key"));
?>