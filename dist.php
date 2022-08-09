<?php

require_once "config.php";
session_start();
$sql = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql);


function getDistance($addressFrom, $addressTo, $unit = ''){
    // Google API key
    $apiKey = 'AIzaSyBHRM-ggFhlphsnVmPhMKHyqhsw1xCDrK4';
    
    // Change address format
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);
    
    // Geocoding API request with start address
    $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
    $outputFrom = json_decode($geocodeFrom);
    if(!empty($outputFrom->error_message)){
        return $outputFrom->error_message;
    }
    
    // Geocoding API request with end address
    $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
    $outputTo = json_decode($geocodeTo);
    if(!empty($outputTo->error_message)){
        return $outputTo->error_message;
    }
    
    // Get latitude and longitude from the geodata
    $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
    
    // Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2).' km';
    }elseif($unit == "M"){
        return round($miles * 1609.344, 2).' meters';
    }else{
        return round($miles, 2).' miles';
    }
}

if(!empty($_POST['origin']) && !empty($_POST['destination'])){
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];

    $addressFrom = $origin;
    $addressTo   = $destination;
}
else{
    $addressFrom = 'Mumbai';
    $addressTo   = 'Mumbai';  
}

$distance = getDistance($addressFrom, $addressTo, "K");

$sql = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$user = mysqli_fetch_array($sql);

$sqli = mysqli_query($conn, "SELECT * FROM booking_details");
$details = mysqli_fetch_array($sqli);

if(array_key_exists('button1', $_POST)){
    if(!isset($details['driver'])){

        $user = $_POST['client'];
        $driver = $_POST['driver'];
        $distance = $_POST['distance'];
        $source = $_POST['source'];
        $destination = $_POST['destination'];
        $travel_cost = $_POST['travelcost'];
        $total = $_POST['total'];
        $payment = 0;

        $sql1 = "INSERT INTO booking_details (distance, source, destination, travel_cost, total, client, driver, payment_status) values ('$distance', '$source', '$destination', '$travel_cost', '$total', '$user', '$driver', '$payment')";;
        $sql2 = "UPDATE drivers set booking_status = 1 WHERE username = '$driver' ";
        $sql3 = "UPDATE driverpost set booking_status = 1 WHERE username = '$driver' ";
        
        if(mysqli_query($conn, $sql1)){
            mysqli_query($conn, $sql2);
            mysqli_query($conn, $sql3);

            header("Location: /TruckLoader/payment.php?driver='$driver' ");
            exit();
        }else{
            echo "Something went Wrong";
        }
    }else{
        $_SESSION['Error'] = "Driver Already Booked";
    }
}

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
    <script src="googlemap.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/welcome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    
  </head>
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
<div class="container">
  <h3>See the Directions</h3>
  <h3><?php 
    $var_value = $_GET['name'];  
    ?>
    </h3>
    <!--The div element for the map -->
    <div id="map"></div>
</div>

<div class="container">
    <?php 
        $query = "select * from driverpost where name = '$var_value'";
        $sql = mysqli_query($conn, $query);
        $arr = mysqli_fetch_array($sql);

        $driver = $arr['username'];
        $query1 = "select * from drivers where username = '$driver'";
        $sql1 = mysqli_query($conn, $query1);
        $arr1 = mysqli_fetch_array($sql1);

        switch($arr1['type_of_truck']){
            case 'TATA ACE':{
                $deiselreq = (int)$distance/20;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'TATA 407':{
                $deiselreq = (int)$distance/10;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'TATA 12 Feet':{
                $deiselreq = (int)$distance/10;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'Canter 14 Feet':{
                $deiselreq = (int)$distance/9;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'LPT 17 Feet':{
                $deiselreq = (int)$distance/9;
                $perkm = $deiselreq*100;
            } 
            break;
            case '1109 -19Feet':{
                $deiselreq = (int)$distance/9;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'LPT 18 Feet':{
                $deiselreq = (int)$distance/9;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'Taurus 22 Feet':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'Taurus 24-25 Feet':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'Taurus 25-26 Feet':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'ASHOK LEYLAND DOST':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'MAHINDRA BOLERO':{
                $deiselreq = (int)$distance/12;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'TATA 22 Feet':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'CONTAINER 32 FT SXL':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case 'CONTAINER 32 FT MXL':{
                $deiselreq = (int)$distance/8;
                $perkm = $deiselreq*100;
            } 
            break;
            case '20 Feet Open All Side':{
                $deiselreq = (int)$distance/7;
                $perkm = $deiselreq*100;
            } 
            break;
            case '20′ Container':{
                $deiselreq = (int)$distance/6;
                $perkm = $deiselreq*100;
            } 
            break;
            case '24′ Container':{
                $deiselreq = (int)$distance/6;
                $perkm = $deiselreq*100;
            } 
            break;
            case '28′ Container':{
                $deiselreq = (int)$distance/6;
                $perkm = $deiselreq*100;
            } 
            break;
            case '32′ Container':{
                $deiselreq = (int)$distance/6;
                $perkm = $deiselreq*100;
            } 
            break;
            case '34′ Container':{
                $deiselreq = (int)$distance/5;
                $perkm = $deiselreq*100;
            } 
            break;
            case '40′ Container':{
                $deiselreq = (int)$distance/4;
                $perkm = $deiselreq*100;
            } 
            break;
        }
        $labour = 500;
    ?>
    <br><br><br>
    <form action="" method="POST" id="distForm">
        <div class="form-row">
            <div class="form-group col">
                <label for="origin">Origin</label>
                <input type="text" id="origin" name="origin" class="form-control" value="<?php echo $arr['city']?>"/>
            </div>
            <div class="form-group col">
                <label for="destination">Destination</label>
                <input type="text" id="destination" name="destination" class="form-control"/>
            </div>
        </div>
        <button class="btn btn-primary" type="submit"> Get Details</button>
        <button class="btn btn-secondary"> Clear</button>
    </form>

    <div class="container">
        
        <div class="row">
            <div class="col">
                <h4>Distance: </h4>
            </div>
            <div class="col">
                <h4><?php echo $distance?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Source: </h4>
            </div>
            <div class="col">
                <h4><?php echo $arr['city'];?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Destination: </h4>
            </div>
            <div class="col">
                <h4><?php
                    if(isset($_POST['destination'])){
                        echo $_POST['destination'];
                    }else{
                        echo " ";
                    }
                ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Driver Name: </h4>
            </div>
            <div class="col">
                <h4><?php echo $var_value;?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Truck: </h4>
            </div>
            <div class="col">
                <h4><?php echo $arr1['type_of_truck']; ?> </h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Travel Cost: </h4>
            </div>
            <div class="col">
                <h4><?php 
                    if($distance>500){
                        $perkm = $perkm*5;
                    }else if($distance>1000){
                        $perkm = $perkm*7;
                    }else if($distance>1500){
                        $perkm = $perkm*9;
                    }
                    echo "₹".number_format((float)$perkm,2,'.','');
                ?> </h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Base Price: </h4>
            </div>
            <div class="col">
                <h4><?php echo "₹".$arr['base_price']; ?> </h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Labour Cost: </h4>
            </div>
            <div class="col">
                <h4> <?php echo "₹".$labour ?> </h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <h4>Total: </h4>
            </div>
            <div class="col">
                <h4> <?php 
                    $total = $perkm+$arr['base_price']+$labour;
                    echo "₹".number_format((float)$total,2,'.','');
                    ?> 
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" style="width: 100%;" data-toggle="modal" data-target="#exampleModalCenter">Confirm Booking</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php 
            if( isset($_SESSION['Error']) )
            { ?>
                <h5 style="color: red;">
                    <?php 
                        
                            echo $_SESSION['Error'];
                        
                            unset($_SESSION['Error']);
                    ?>
                </h5>
        <?php }else{ ?>
        <h5 class="modal-title" id="exampleModalLongTitle">Confirm Booking</h5>
        <?php } ?>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST">
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="hidden" class="form-control" id="src" name="client" value="<?php echo $user['username']; ?>">
                    <input type="hidden" class="form-control" id="src" name="driver" value="<?php echo $arr['username']; ?>">
                    <label for="src">Driver Name: </label>
                    <input class="form-control" id="src" name="driver1" value="<?php echo $arr['name']; ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="distance">Distance: </label>
                    <input class="form-control" id="distance" name="distance" value="<?php echo $distance; ?>" readonly>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="source">Source: </label>
                    <input class="form-control" id="source" name="source" value="<?php echo $arr['city']; ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="dest">Destination: </label>
                    <input class="form-control" id="dest" name="destination" value="<?php if(isset($_POST['destination'])){
                        echo $_POST['destination'];
                    }else{
                        echo " ";
                    } ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="src">Travel Cost: </label>
                    <input class="form-control" id="src" name="travelcost" value="<?php echo number_format((float)$perkm,2,'.',''); ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="distance">Total: </label>
                    <input class="form-control" id="distance" name="total" value="<?php echo number_format((float)$total,2,'.',''); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" name="button1">Confirm</button>
            
        </div>
      </form>
    </div>
  </div>
</div>

<div class="conatiner">
    
</div>

    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHRM-ggFhlphsnVmPhMKHyqhsw1xCDrK4&callback=initMap&v=weekly"
      
    ></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>
</html>