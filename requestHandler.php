<?php
include 'config.php';

function request_API($path){
    // Create connection
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

/*    
    //example of using the data...
    foreach ($data['Stops'] as $key => $value) {
        print_r($value);
        foreach ($value as $k => $val) {
            print_r($k);
        }
    }
    die();*/

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

?>
