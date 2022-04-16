<?php
/*

*/

define('DB_SERVER','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','truckloader');

//Try Connecting to database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Check Connection
if($conn == false){
    dir('Error: Cannot connect');
}
?>