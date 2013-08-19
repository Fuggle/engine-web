<?php 
	include('config.php');

	print_r("host:".$host." user: ".$username." pass: ".$password." db: ".$db."<br />");

	// Create connection
	$con=mysqli_connect($host, $username, $password, $db);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	function request_API($path){
	    // Create connection
	    global $host, $username, $db, $password;
	    $con=mysqli_connect($host, $username, $password, $db);

	    if (mysqli_connect_errno()) {
	        printf("Connect failed: %s\n", mysqli_connect_error());
	        exit();
	    }

	    $opia_username="tran.khoa";
	    $opia_password="wNT}MGc@y+k0";
	    
	    $url="https://opia.api.translink.com.au/v1/";
	    
	    $headers = array('Accept: applicationk/json','Content-Type: application/json');
	    

	    //intiitial ther cURL 
	    $curl = curl_init();
	    
	    curl_setopt($curl, CURLOPT_URL, $url.$path);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($curl, CURLOPT_USERPWD,$opia_username.':'.$opia_password);
	    $resp = curl_exec($curl);

	    $data = parseJSON($resp);

	    $sql_string = "INSERT INTO `API_Cache`.`caches` (`cache_ID`, `time_stamp`, `stored_responds`) VALUES (NULL, CURRENT_TIMESTAMP,".$resp." )";
	    mysqli_query($con, $sql_string);
	    mysqli_close($con);


	    
	    echo ('<hr>'.curl_getinfo($curl, CURLINFO_HEADER_OUT));
	    curl_close($curl);
	   
	}

	/*
	Takes a raw json string and decodes it.
	Builds an associative array of useful information.
	Returns array.
	*/
	function parseJSON($json) {
	    $parse = json_decode($json, true);
	    return $parse;
	}

	/*
	TODO: all-purpose function that...
	takes an array and a string (table name)...
	pushes the given array into the specified table.
	*/
	function pushData(array $data, string $table) {
		print_r($table);
	}

	//this will return an array of all vehicles
	//array as [[tip_id, route_id, latitude, longitude], ..., [N, N, N, N]] 
	//$route is OPTIONAL... if given, it will look for this route 
	//only (by route id), and return an array of just that info.
	function getRoutes($route = null) {
		//this will be used to build the category sections on any page
		global $data;
		$results = $data['vehicle_pos'];

		if ($route != null) {
			$result = array();
			foreach ($results as $value) {
				if ($route == $value['route_id']) {
					array_push($result, $value);
				}
			}
			return $result;
		} else {
			return $results;
		};
	};

?>