<?php
require_once "config.php";
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
}

$sql = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql);

if(isset($_POST["submit"])){
  if(!empty($_POST['city']) && !empty($_POST['price'])){
    $city = $_POST['city'];
    $price = $_POST['price'];

    $sql = "SELECT * FROM driverpost WHERE city = '$city' AND base_price <= '$price' ";
    if(mysqli_query($conn, $sql)){
      header("Location: welcome.php");
    }else{
      echo "Error Filtering";
    }

  }else{
    echo "Please enter all fields";
  }
}



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
            <li class="nav-item">
                <a class="nav-link" href="booking.php"> Your Booking </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li> 
        </ul>
      </div>
  </div>
</nav>



<div class="container">
  <form method="POST">
    <div class="row">
      <div class="col-md">
        <div class="form-group">
          <label for="exampleFormControlSelect1">City</label>
          <select class="form-control" id="exampleFormControlSelect1" name="city">
            <option value="Delhi">Delhi</option>
            <option value="Mumbai">Mumbai</option>
            <option value="Banglore">Banglore</option>
            <option value="Mysore">Mysore</option>
            <option value="Hyderabad">Hyderabad</option>
          </select>
        </div>
      </div>
      <div class="col-md">
        <label for="">Price</label>
        <input class="form-control" type="number" name="price">
      </div>
      <div class="col-md">
        <br>
        <button class="btn btn-primary" type="submit" name="filter">Filter<i class="fa-solid fa-filter" style="font-size: 20px;"></i></button>
        <button class="btn btn-secondary" name="all">All</button>
      </div>
    </div>
  </form>
</div>

<div class="cards">
  <?php if(array_key_exists('filter', $_POST)){
    $city = $_POST['city'];
    $price = $_POST['price'];
    $query = mysqli_query($conn, "SELECT * FROM driverpost WHERE city = '$city' AND base_price <= '$price'");  
  }else{
    $query = mysqli_query($conn, "SELECT * FROM driverpost");
  }
  
  ?>

  <?php foreach($query as $a): ?>
    <div class="card" style="width: 18rem;">
      <img class="card-img-top" src="upload/<?php echo $a["truck_image"]?>" alt="Card image cap" height="250px" width="250px">
      <div class="card-body">
        <h5 class="card-title">Driver: <?php echo $a['name']; ?></h5>
        <?php $sql1 = mysqli_query($conn, "SELECT * FROM drivers where username = '".$a['username']."'");
        $arr2 = mysqli_fetch_array($sql1);
        ?>
        <h5>Base Price: <?php echo $a['base_price']?></h5>
        <h5><?php echo $arr2['type_of_truck']?></h5>
        <h5>Location: <?php echo $a['city']?></h5>
        <a href="dist.php?name=<?php echo $a['name'] ?>" class="btn btn-secondary">Book</a>
      </div>
    </div>
  <?php endforeach ?>
</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  </body>
</html>