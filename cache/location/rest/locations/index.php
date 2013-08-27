<?php
	//expects location id or multiple ids, eg: ?ids=LM:Places+Of+Interest:Lockyer+Valley+Cultural+Centre

	//gets one or more locations by their ID. All information for that location.
	// (hint: location/rest/resolve can be used to search for locations.)
	include('../../../cache_functions.php');
	$id=$_GET['ids'];
	$bypassCahe=false;
	cache_engine(("location/rest/locations?ids=".urlencode($id)."&api_key=special-key"));
?>