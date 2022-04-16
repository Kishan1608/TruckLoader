<?php 
require_once "config.php";
session_start();

$driver = $_GET['driver'];

$sql = "UPDATE drivers SET booking_status=NULL WHERE username = '$driver' ";
if(mysqli_query($conn, $sql)){
    mysqli_query($conn,"DELETE FROM booking_details WHERE driver = '$driver' ");
    header("Location: booking.php");
}

?>