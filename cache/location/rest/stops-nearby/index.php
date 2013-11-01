<?php

	// this page take in the location Id  parameter radiusM number useWalkingDistance and number of results
	// and return near by stop NearbyStops
	include('../../../cache_functions.php');
	$radiusM=$_GET['radius'];
	$id=$_GET['id'];
	
	//cache_engine("location/rest/stops-nearby/".$id."?radiusM=".$radiusM."useWalkingDistance=true&maxResults=30&api_key=special-key");
	echo cache_engine(("location/rest/stops-nearby/".$id."?radiusM=".$radiusM."useWalkingDistance=true&maxResults=30&api_key=special-key"));
?>