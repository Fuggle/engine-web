<?php 
	include('config.php');

	//print_r("host:".$host." user: ".$username." pass: ".$password." db: ".$db."<br />");

	// Create connection
	$con=mysqli_connect($host, $username, $password, $db);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	//first, we want to build a data array of the information that we need
	//then we can refer to this array instead of reading from the database
	//each time we need to fetch information.
	$data = array();

	//build location information
	$locquery = "SELECT * FROM vehicle_pos";
	$locresult = mysqli_query($con, $locquery);
	$data['vehicle_pos'] = array();
	while($locrow = mysqli_fetch_array($locresult, MYSQLI_ASSOC)) {
		array_push($data['vehicle_pos'], array_filter($locrow));
	};

	/* close connection */
	mysqli_close($con);

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