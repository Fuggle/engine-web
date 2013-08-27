<?php
//network/rest/stop-timeables

	// this page take in the stop ids parameter and return detail information about that specific stop id 's time table
	//https://opia.api.translink.com.au/v1/network/rest/stop-timetables?stopIds=000043&date=27+aug+2013
	include('../../../cache_functions.php');
	date_default_timezone_set('Australia/Queensland');
	
	$stopIds=$_GET['stopIds'];
	$today= date('j+M+Y');
	//echo $date;
	$weekday=date('D');
	$bypassCahe=false;
	cache_engine(("network/rest/stop-timetables?stopIds=".$stopIds."&date=".$today),("stop_".$stopIds."_timestable_on_".$weekday));// return the time with utc added 7 day.
?>