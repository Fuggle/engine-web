<?php
	//expects route codes and vehicle type, eg: ?routes=109,333&type=2

	// This page takes a list of route ids and a vehicle type and returns completed timetables for those routes.
	// this data will be used where we are unable to fetch real-time data.
	include('../../../cache_functions.php');
	date_default_timezone_set('Australia/Queensland');
	
	$routecodes=$_GET['routes'];
	$type=$_GET['type'];
	$today= date('j+M+Y');
	$weekday=date('D');
	$bypassCahe=false;
	cache_engine(("network/rest/route-timetables?routeCodes=".urlencode($routecodes)."&vehicleType=".$type."&date=".$today."&filterToStartEndStops=false&api_key=special-key"),("route_".$routecodes."_timetable_on_".$weekday));// return the time with utc added 7 day.
?>