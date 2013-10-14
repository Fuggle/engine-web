<?php 
	include('php_fast_cache.php');
	/*
		this function takes in a paths_querry, 
		this will check with the cache databse, 
		if the data was cached, the cached data will be return, 
		if the data was not cached, we will querry translink record to cache, and return the result
	
	/*
	This function handles the caching of static data from OPIA.
	This means that if we already have the data cached, no call to OPIA needs to be made.
	*/
	function cache_engine($path_querry,$cacheID = null){ 
		$cacheTime=7;// 7 days cache 
		
	    $cache = new phpFastCache("auto");
	    
	    $opia_username="tran.khoa";
		$opia_password="wNT}MGc@y+k0";
	     
	    $url=("https://opia.api.translink.com.au/v1/".$path_querry);
	    //echo($url);
		    if($cacheID==null){
			     $results = $cache->get($url);
		    }else{
			     $results = $cache->get($cacheID);
		    }
		    
		    if($results == null) {
		        			    
			    $headers = array('Accept: application/json','Content-Type: application/json');
			    //intiitial ther cURL 
			    $curl = curl_init();
			    
			    curl_setopt($curl, CURLOPT_URL, $url);
			    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
			    curl_setopt($curl, CURLOPT_USERPWD,$opia_username.':'.$opia_password);
			    $resp = curl_exec($curl);
		        curl_close($curl);
		        
		        // Write to Cache Save API Calls next time
		       
		        if($cacheID==null){
			     	 $cache->set($url, $resp, 3600*24*$cacheTime);// cache to database 
			     	 $results = $cache->get($url);
			    }else{
			     	$cache->set($cacheID, $resp, 3600*24*$cacheTime);// cache to database 
			     	$results = $cache->get($cacheID);
			    }
			    //echo ("<div style='color:red;'>");
			    //print_r($results);
			    //echo ("</div>");
			    //echo ("<div style='color:red'>Not cached</div>");
		        
		    }
		    else{
		    	//echo ("<div style='color:blue;'>");
			    //print_r($results);
			    //echo ("</div>");
		    	//echo ("<div style='color:blue'>Cached</div>");
		    }
		    return $results;
    }
    
    

    /*
	Handles querying the OPIA API. Takes a path string (eg. "location/rest/resolve?input=X") 
	and returns the response given by OPIA.
    */
    function querry_opia($path_querry){
	    $opia_username="tran.khoa";
		$opia_password="wNT}MGc@y+k0";
	     
	    $url=("https://opia.api.translink.com.au/v1/".$path_querry);
	    //echo($url);
	    $headers = array('Accept: application/json','Content-Type: application/json');
		//intiitial ther cURL 
	    $curl = curl_init();
	    
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($curl, CURLOPT_USERPWD,$opia_username.':'.$opia_password);
	    $resp = curl_exec($curl);
        curl_close($curl);
        
	    //print_r($resp);
        return $resp;  
    }
    

    /*
    	get stops from a location
    	id is a location ID reslove from translink
    	radius is the range to look for
    	max result is the number of stops to be returned
    	
    	Return the stops arround that location in a json string.
    */
    
    function get_stops_from_location($id,$radiusM,$maxResults){
	    $useWalkingDistance=true;
    	$result=cache_engine("location/rest/stops-nearby/".$id."?radiusM=".$radiusM."&useWalkingDistance=true"."&maxResults=".$maxResults,$id);
    	return $result; 
    }
    
    /*
    	get all the stops information of a specific stop
    */
    
    function get_stop_details($id){
	    $result=cache_engine(("location/rest/stops?ids=".$id));
	    return $result;
    }
    /*
    	convert_strings will take a string and
    	conver space to %20, and : to 3%A, and , to %2C 
    	this the string will be sent back to querry opia or send to caches database
    */
    function convert_strings($string){
	    $string=str_replace(" ","%20",$string);
	    $string=str_replace(":","%3A",$string);
	    $string=str_replace(",","%2C",$string);
	    return $string;
    }
    
    
    function searchAll($array, $key)
	{
	    $results = array();
	
	    if (is_array($array))
	    {
	        if (isset($array[$key]) && $array[$key])
	            $results[] = $array;
	
	        foreach ($array as $subarray)
	            $results = array_merge($results, search($subarray, $key));
	    }
	
	    return $results;
	}

    
    
    function find_all($needle, array $haystack, array &$result = null) {
    // This is to initialize the result array and is only needed for
    // the first call of this function
	    if(is_null($result)) {
	        $result = array();
	    }
    
	    foreach($haystack as $key => $value) {
	        // Check whether the key is the value we are looking for. If the value
	        // is not an array, add it to the result array.
	        if($key === $needle && !is_array($value)) {
	            $result[] = $value;
	            break;
	        }
	        if(is_array($value)) {
	            // If the current value is an array, we perform the same
	            // operation with this 'subarray'.
	            find_all($needle, $value, $result);
	        }
	    }
	    // This is only needed in the first function call to retrieve the results
	    return $result;
	}
    
    
    function ArraySearchRecursive($Needle,$Haystack,$NeedleKey="",$Strict=false,$Path=array()) {
	  if(!is_array($Haystack)){
		 return false; 
	  }
	    
	  foreach($Haystack as $Key => $Val) {
	    if(is_array($Val)&&$SubPath=ArraySearchRecursive($Needle,$Val,$NeedleKey,$Strict,$Path)) {
	      $Path=array_merge($Path,Array($Key),$SubPath);
	      return $Path;
	    }
	    elseif((!$Strict&&$Val==$Needle&&
	            $Key==(strlen($NeedleKey)>0?$NeedleKey:$Key))||
	            ($Strict&&$Val===$Needle&&
	             $Key==(strlen($NeedleKey)>0?$NeedleKey:$Key))) {
	      $Path[]=$Key;
	      return $Path;
	    }
	  }
	  return false;
	}
?>