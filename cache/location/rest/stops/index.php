<?php

	// this page take in the stop ids parameter and return detail information about that specific stop id
	include('../../../cache_functions.php');
	$id=$_GET['ids'];
	cache_engine("location/rest/stops?ids=".$id);
?>