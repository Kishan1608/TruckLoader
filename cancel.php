<?php 
require_once "config.php";
session_start();

$driver = $_GET['driver'];

$sql = "UPDATE drivers SET booking_status=0 WHERE username = '$driver' ";
if(mysqli_query($conn, $sql)){
    mysqli_query($conn,"DELETE FROM booking_details WHERE driver = '$driver' ");
    mysqli_query($conn, "UPDATE driverpost SET booking_status=0 WHERE username = '$driver'");
    header("Location: booking.php");
}

?>