<?php 
//location/rest/resolve
	include('../../../cache_functions.php');
	$resolve_input=$_GET['input'];
	if( isset($_GET['filter'],$_GET['maxResults'])){
		$filter=$_GET['filter'];
		$maxResults=$_GET['maxResults'];
	}else{
		$filter='0';
		$maxResults='10';
		
	}
	//echo $resolve_input;
	querry_opia(("location/rest/resolve?input=".$resolve_input."&filter=".$filter."&maxResults=".$maxResults));
	
	
?>