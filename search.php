<?php 

    $query=$_GET['query'];
    cache_engine("location/rest/stops?query=".$query);

    function cache_engine($path_querry){ 
 
	    $cache = new phpFastCache("auto");
	    $opia_username="tran.khoa";
		$opia_password="wNT}MGc@y+k0";
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
