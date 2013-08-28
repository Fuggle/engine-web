<?php 
	include("../../../cache_functions.php");

	//takes a query text string and returns possible locations / stops etc.
    $query=$_GET['query'];
    echo querry_opia("location/rest/resolve?input=".urlencode($query)."&filter=0&maxResults=25&api_key=special-key");
?>