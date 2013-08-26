<?php 

    $query=$_GET['query'];
    search("location/rest/resolve?input=".$query."&filter=0&maxResults=25&api_key=special-key");

    /*
	This handles searching for locations, stops etc.
	Takes a path + search query and returns an array of 
	search results or null if there is none.
    */
    function search($path_querry){ 
 
	    $opia_username="tran.khoa";
		$opia_password="wNT}MGc@y+k0";
	    $url=("https://opia.api.translink.com.au/v1/".$path_querry);
	    
	    $headers = array('Accept: application/json','Content-Type: application/json');
	    

	    //intiitial ther cURL 
	    $curl = curl_init();
	    
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($curl, CURLOPT_USERPWD,$opia_username.':'.$opia_password);
	    $resp = curl_exec($curl);
	
	    if($resp == null) {
	    	print_r(null);
	    } else {
	    	return $resp;
	    }
	    //return $cache->get($url);
    }

?>
