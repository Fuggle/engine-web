<?php 
	include('php_fast_cache.php');
	
	function cache_engine($path_querry,$cacheID){ 
	
	    $cache = new phpFastCache("auto");
	    
	    $opia_username="tran.khoa";
		$opia_password="wNT}MGc@y+k0";
	     
	    $url=("https://opia.api.translink.com.au/v1/".$path_querry);
	    echo($url);
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
		     	 $cache->set($url, $resp, 3600*24*7);// cache to database 
		     }else{
		     	$cache->set($cacheID, $resp, 3600*24*7);// cache to database 
		     }	        
	        echo ("<div style='color:red'>".$resp."</div>");
	    }
	    else{
	    	echo ("<div style='color:blue'>".$results."</div>");
	    }
	    //return $results;
    }
?>