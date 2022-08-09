<?php
require_once "config.php";

session_start();
$sql = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql);

$driver = $_GET['driver'];

$sql1 = mysqli_query($conn,"SELECT * FROM drivers WHERE username = $driver ");
$arr2 = mysqli_fetch_array($sql1);


$query = "SELECT * FROM booking_details WHERE driver = $driver";
$sql2 = mysqli_query($conn, $query);
$details = mysqli_fetch_array($sql2);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VESIT LOGISTICS</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="index.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/welcome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function paynow(){

        var name = document.getElementById("name").value;
        var email = document.getElementById("email").value;
        var phone = document.getElementById("mobile").value;
var options = {
    "key": "rzp_test_xIsAMF8dYahRYn", // Enter the Key ID generated from the Dashboard
    "amount": <?php echo $details['total'] ?> * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": "TruckLoader",
    "description": "VESIT Logistic",
    "image": "./upload/truck-logo-cargo-logo-delivery-cargo-trucks-log-vector-26486067.jpg",
    //"order_id": "order_IluGWxBm9U8zJ8", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        alert("Payment Successfull");
        if (typeof response.razorpay_payment_id == 'undefined' ||  response.razorpay_payment_id < 1) {
            redirect_url = '/TruckLoader/payment.php';
        } else {
            <?php 
                $sql = mysqli_query($conn, "UPDATE booking_details SET payment_status = 1 WHERE client = '".$_SESSION['username']."' ");
            ?>
            redirect_url = '/TruckLoader/booking.php';
        }
        location.href = redirect_url;
    },
    "prefill": {
        "name": name,
        "email": email,
        "contact": phone
    },
    "notes": {
        "address": "Razorpay Corporate Office"
    },
    "theme": {
        "color": "#3399cc"
    }
};
var rzp1 = new Razorpay(options);
rzp1.on('payment.failed', function (response){
        alert(response.error.code);
        alert(response.error.description);
        alert(response.error.source);
        alert(response.error.step);
        alert(response.error.reason);
        alert(response.error.metadata.order_id);
        alert(response.error.metadata.payment_id);
});

    rzp1.open();

}
</script>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.html">TruckLoader</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>


      <div class="navbar-collapse collapse">
      <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#"><?php echo "Welcome  ".$arr['name'] ?></a>
            </li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li> 
        </ul>
      </div>
  </div>
</nav>
    <div class="container" >
        <div class="row" style="margin: 20px;">
            <div class="col">
                <h4>Driver: </h4>
            </div>
            <div class="col">
                <h4> <?php echo $arr2['name']; ?> </h4>
            </div>
        </div>

        <div class="row" style="margin: 20px;">
            <div class="col">
                <h4>Source: </h4>
            </div>
            <div class="col">
                <h4> <?php echo $details['source']; ?> </h4>
            </div>
        </div>

        <div class="row" style="margin: 20px;">
            <div class="col">
                <h4>Destination: </h4>
            </div>
            <div class="col">
                <h4> <?php echo $details['destination']; ?> </h4>
            </div>
        </div>

        <div class="row" style="margin: 20px;">
            <div class="col">
                <h4>Distance: </h4>
            </div>
            <div class="col">
                <h4> <?php echo $details['distance']; ?>km.</h4>
            </div>
        </div>

        <div class="row" style="margin: 20px;">
            <div class="col">
                <h4>Total: </h4>
            </div>
            <div class="col">
                <h4> ₹<?php echo $details['total']; ?>. </h4>
            </div>
        </div>
    </div>

    <div class="container">
        <input type="text" name="name" id="name" placeholder="Your Name: " class="form-control"><br>
        <input type="email" name="email" id="email" placeholder="Your Email: " class="form-control"><br>
        <input type="tel" name="mobile" id="mobile" placeholder="Your Number" class="form-control"><br>

        <button id="rzp-button1" onclick="paynow()" class="btn btn-primary">Pay</button>
    </div> 
</body>
</html>



