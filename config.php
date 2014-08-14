<?php

/**
 * @file
 * A single location to store configuration.
 */
define('CONSUMER_KEY', 'Your Consumer Key');
define('CONSUMER_SECRET', 'Your Consumer Secret');
define('OAUTH_CALLBACK', 'http://yoursitename/callback.php');
$servername = 'localhost';
$hostname = 'root'; 
$password = '';
$dbname = 'vitwitapp';
$con = mysqli_connect($servername, $hostname, $password, $dbname);
//Check Connection
if(mysqli_connect_errno($con)){
   echo "Failed to connect to db :".mysqli_connect_error();
}
