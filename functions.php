<?php 
	include('config.php');

	print_r("host:".$host." user: ".$username." pass: ".$password." db: ".$db."<br />");

	// Create connection
	$con=mysqli_connect($host, $username, $password, $db);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	
	//This function will request the translink data base directly
	//and record the respond into the cache database with a timestamp
	//it will return a associated array of rendeed object
	function request_API($path, $parameters){
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
	    
	    curl_setopt($curl, CURLOPT_URL, $url.$path.'?'.$parameter);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($curl, CURLOPT_USERPWD,$opia_username.':'.$opia_password);
	    $resp = curl_exec($curl);

	    $query = "SELECT * FROM API_Cache.date_test";
	    $result = mysqli_query($con, $query);
	    $rows = mysqli_fetch_array($result, MYSQLI_ASSOC);
	    mysqli_close($con);
	    print_r($rows);
	    die();

	    $data = parseJSON($resp);
<<<<<<< HEAD
	    pushStopData($data);
=======
	    pushData($data, "caches");
>>>>>>> 11810325fddbc59f6f552ff9a2cd56e407229d86
	    die();

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
<<<<<<< HEAD
	function pushStopData(array $data) {
=======
	function pushData(array $data, $table) {
		printf("push Data function called");
>>>>>>> 11810325fddbc59f6f552ff9a2cd56e407229d86
		global $host, $username, $db, $password;
	    $con=mysqli_connect($host, $username, $password, $db);

	    if (mysqli_connect_errno()) {
	        printf("Connect failed: %s\n", mysqli_connect_error());
	        exit();
	    }

	    $query = "SELECT * FROM API_Cache.date_test";

	    $result = mysqli_query($con, $query);
	    mysqli_close($con);
	    print_r($result);
	    die();

	    $fields = mysqli_fetch_fields($result);
	    $columns = array();
	    foreach ($fields as $key => $value) {
	    	array_push($columns, $value->name);
	    }

	    foreach ($data as $key => $value) {
	    	if (in_array($key, $columns)) {
	    		print_r('key is in columns');
	    	}
	        foreach ($value as $ke => $val) {
	            print_r($val);
	            foreach ($val as $k => $v) {
	            	print_r($k);
	            }
	        }
	    }
	    die();
	    mysqli_close($con);
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

	/*
	Caching logic 
	caching will take a path and return a Assosiative array
	Select the path from cache database,
	if data stored in the cache is new, then get information from database.
	if data is expired, then querry OPIA and update the cache.
	$parameter will be used to check if that record exsited in the database
	*/
	function cache_logic($rest_path,$parameter) { //may be change the function name to make more sense? 
	    $path_array = decompose_rest_path($rest_path);
	    $model = $path_array[0];
	    $control = $path_array[2];
	    switch ($model) {
	        case "version":
	            // get direcly from the cache: 
	        	switch ($control) {
	        		case 'api':
	        			# code...
	        			break;
	        		case '';
	        		default:
	        			# code...
	        			break;
	        	}
	            printf("model=version");
	            break;
	        case "travel":
	            //Must querry the OPIA api dirrectly do not need to check the cache database
	            printf('model=travel');
	            break;
	        case "location":
	            //Check the location cache database 
	            
	            switch ($control){
		            case "stop-timetables": 
			            /*
			            path string should be: location/rest/stop-timetables
			            required parameters: 	stopIds (eg:000043)
			            					 	date (FRI 23 AUG 2013) - time component will be ignored
			            */		            
		            	break;
		            case "route-timetables":
			            /*
			            path string should be: location/rest/routes-timetables
			            required parameters: 	routeCodes (eg:000043) The route codes or train lines to retrieve timetables for. Hard limit of 50 Route Codes per request	
			            					 	date (FRI 23 AUG 2013) - time component will be ignored
			            					 	**MORE INFO: https://opia.api.translink.com.au/v1/content/swaggerui/index.aspx#!/network/trips_get_2
			            */
		            	break;
		            case "trips":
			            /*
				            
			            */
		            	break;
		            case "trip-map-path":
		            	break;
		            case "route-map-path":
		            	break;
		            case "routes":
		            	break;
		            case "route-line":
		            	/*
			            	
		            	*/
		            	break;
		            default:
		            	//return error if 
		            	break;		
	            }
	            printf('model=location');
	            break;
	        case "network":
	            //querry the OPIA api dirrectly do not need to check the cache database
	            printf('model=network');
	            break;
	        default :
	            // return error: No matches service, please check your path.
	            break;
	    }
	}
	
	function decompose_rest_path($rest_path) { //$rest_path=location/rest/reslove 
	   return list($model, $control, $view) = explode("/", $rest_path);
	}
?>