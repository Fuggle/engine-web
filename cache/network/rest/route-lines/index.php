<?php
	//expects type. Eg: ?type=8

	// this applies only to trains and ferries. retrieves all routes travelling on a day. returns by line.
	include('../../../cache_functions.php');
	date_default_timezone_set('Australia/Queensland');

	$today= date('j+M+Y');
	$weekday=date('D');
	$bypassCahe=false;
	$type=$_GET['type'];
	echo cache_engine(("network/rest/route-lines?vehicleType=".$type."&date=".$today."&api_key=special-key"),("ALL_lines_on_".$weekday));
?>