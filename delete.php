<?php
require_once "config.php";
session_start();

$sql = mysqli_query($conn, "DELETE FROM driverpost WHERE username = '".$_SESSION['username']."'");

header("Location: driverWelcome.php");
?>