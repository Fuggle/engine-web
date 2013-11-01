<?php
	include('php_fast_cache.php');
    $id=$_GET['ids'];
    //cache_engine("network/rest/routes?date=24+Aug+2013");
    cache_engine("location/rest/stops?ids=".$id);
    //cache_engine();
    function cache_engine($path_querry){ 
    	//$path_querry="network/rest/routes?date=24+Aug+2013";  
	    $cache = new phpFastCache("auto");
	    include('translink_config.php');
	    //$url=("https://opia.api.translink.com.au/v1/network/rest/routes?date=24+Aug+2013");
	    $url=("https://opia.api.translink.com.au/v1/".$path_querry);
	    
	    $results = $cache->get($url);
	
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
	        $cache->set($url, $resp, 3600*24*7);// cache to database 
	        echo ("<div style='color:red'>".$cache->get($url)."</div>");
	    }
	    else{
	    	echo ("<div style='color:blue'>".$cache->get($url)."</div>");
	    }
	    //return $cache->get($url);
    }
    
    
?>