<?php 
	include("../cache/cache_functions.php");
	
	$tripID=$_GET['tripID'];
	//exit($tripID);
	$opia_result=querry_opia("network/rest/trips?ids=".$tripID);
	//exit($opia_result);
	$result_array=json_decode($opia_result,true);
	//echo(json_encode($result_array));
	$stopsToQuery=($result_array['Trips']['0']['StopIds']);
	$time=($result_array['Trips']['0']['ArrivalTimes']);
	
	
	
	//var_dump($stopsToQuery);
	$PositionArray=array();
	foreach($stopsToQuery as $key => $value )	{
		$stops_info=get_stop_details($value);
		
		$stops_info=json_decode($stops_info,true);
		
		$des=$stops_info['Stops'][0]['Description'];
		
		$position=$stops_info['Stops'][0]["Position"];
		$position['stopID']=$value;
		$position["des"]=$des;
		$position['time']=($result_array['Trips']['0']['ArrivalTimes'][$key]);
		array_push($PositionArray,$position);
	}
	echo(json_encode($PositionArray));
	return(json_encode($PositionArray));

?>