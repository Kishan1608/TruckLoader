<?php 
require_once "config.php";
session_start();

$sql = mysqli_query($conn,"SELECT * FROM booking_details WHERE client = '".$_SESSION['username']."'");

$sql1 = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql1);

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VESIT LOGISTICS</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/booking.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>


        <div class="navbar-collapse collapse">
            <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo "Welcome  ".$arr['name']?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php"> Your Booking </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li> 
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php foreach ($sql as $a): 
            
                $username = $a['driver'];
                $query = mysqli_query($conn, "SELECT * FROM drivers where username='$username' ");
                $arr1 = mysqli_fetch_array($query);


                $query2 = mysqli_query($conn, "SELECT * FROM driverpost where username='$username' ");
                $arr2 = mysqli_fetch_array($query2);
            ?>
            <br><br><br><br>
            <div class="media">
                <img class="align-self-center mr-3" src="upload2/<?php echo $arr1['profile_pic'] ?>" alt="image" height="200px" width="200px">
                <div class="media-body">
                    <h5 class="mt-0"><?php echo "Driver:- ".$arr1['name']?></h5>
                    <h5 class="mt-0"><?php echo "From:- ".$a['source']."  To:- ".$a['destination']?></h5>
                    <h5 class="mt-0"><?php echo "Contact:- ".$arr1['contact']?></h5>
                    <h5 class="mt-0"><?php echo "Truck Number:- ".$arr1['vehicle_number']?></h5>
                    <h5 class="mt-0"><?php echo "Price:- "."₹".$a['total']?></h5>
                    <a href="cancel.php?driver=<?php echo $a['driver'] ?>" class="btn btn-danger"> Cancel Truck</a>
                </div>
            </div>
            <br>
        <?php endforeach?>
    </div>

</body>
</html>
