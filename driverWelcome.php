<?php
require_once "config.php";
session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged'] !== true){
    header("location: driverLogin.php");
}

$sql = mysqli_query($conn,"SELECT * FROM drivers WHERE username = '".$_SESSION['username']."'");
$sql1 = mysqli_query($conn,"SELECT * FROM driverpost WHERE username = '".$_SESSION['username']."'");
$sql2 = mysqli_query($conn,"SELECT * FROM booking_details WHERE driver = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql);


if(isset($_POST["submit"])){
  if(!mysqli_num_rows($sql1)){
  
    if(!empty($_POST['price']) && !empty($_POST['city']) && !empty($_FILES['truck_image'])){
    $price = $_POST['price'];
    $city = $_POST['city'];
    $image = $_FILES['truck_image'];
    $image2 = $_FILES['profile_image'];
    $username = $_SESSION['username'];
    $name = $_POST['name1'];

    $fileName = $_FILES['truck_image']['name'];
    $fileError = $_FILES['truck_image']['error'];
    $fileTemp = $_FILES['truck_image']['tmp_name'];
    $fileSize = $_FILES['truck_image']['size'];
    $fileType = $_FILES['truck_image']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('png','jpg', 'jpeg');

    $fileName1 = $_FILES['profile_image']['name'];
    $fileError1 = $_FILES['profile_image']['error'];
    $fileTemp1 = $_FILES['profile_image']['tmp_name'];
    $fileSize1 = $_FILES['profile_image']['size'];
    $fileType1 = $_FILES['profile_image']['type'];

    $fileExt1 = explode('.', $fileName1);
    $fileActualExt1 = strtolower(end($fileExt1));

    if(in_array($fileActualExt, $allowed)){
      if($fileError === 0){
        if($fileSize < 300000){
          $fileNameNew = uniqid('IMG-', true).".".$fileActualExt;
          $fileNameNew1 = uniqid('IMG-', true).".".$fileActualExt1;
          $fileDEstination = 'upload/'.$fileNameNew;
          $fileDEstination1 = 'upload2/'.$fileNameNew1;
          move_uploaded_file($fileTemp, $fileDEstination);
          move_uploaded_file($fileTemp1, $fileDEstination1);

          $sql = "INSERT INTO driverpost (truck_image, base_price, city, username, name) values ('$fileNameNew','$price', '$city', '$username', '$name')";
          $sql2 = "UPDATE drivers SET profile_pic = '$fileNameNew1' WHERE username = '".$_SESSION['username']."' ";
          if(mysqli_query($conn, $sql)){
            mysqli_query($conn, $sql2);
            echo "Inserted!!!";
            header("Location: driverWelcome.php");
          }else{
            echo "Error Inserting";
          }

        }else{
          echo "Your File is too big ";
        }
      }
    }else{
      echo "You cannot upload file of this type. ";
    }

    }else{
    echo "All fields Required";
    }
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
    <link href="css/driverWelcome.css" rel="stylesheet" />
    <title>Login TruckLoader</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.html">TruckLoader</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>


      <div class="navbar-collapse collapse" id="navbarTogglerDemo01">
      <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#"><?php echo "Welcome Driver ".$arr['name'] ?></a>
            </li>
            <?php if(!mysqli_num_rows($sql1) && !mysqli_num_rows($sql2)) { ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
              Post Ads
            </button>
            <?php } ?>
            &nbsp;&nbsp;&nbsp;
            <a href="driverLogout.php">
            <button type="button" class="btn btn-danger">
              Logout
            </button>
            </a>
        </ul>
      </div>
  </div>
</nav>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Post Advertisement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php if(!mysqli_num_rows($sql1)){?>
        <div class="container">
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col">
              <label for="nameinput">Name</label>
              <input type="text" class="form-control" name="name1" id="nameinput" placeholder="Driver Name">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="cityinput">City</label>
              <input type="text" class="form-control" name="city" id="cityinput" placeholder="Enter City">
            </div>
            <div class="form-group col-md-6">
              <label for="minimumprice">Base Price</label>
              <input type="number" class="form-control" name="price" id="minimumprice" placeholder="Enter Minimum Charges">
            </div>
            <div class="form-group col-md-6">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="truck" name="truck_image">
                <label class="custom-file-label" for="truck">Truck Image</label>
              </div>
            </div>
            <div class="form-group col-md-6">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="truck" name="profile_image">
                <label class="custom-file-label" for="truck">Profile Image</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary">Add</button>
          </div>
          </form>
        </div>
      <?php }else{?>
        <div class="container">
          <h5>You have already posted an Advertisement.</h5>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      <?php }?>
      </div>
    </div>
  </div>
</div>

<?php 
  $driver = mysqli_query($conn, "SELECT * FROM drivers where username = '".$_SESSION['username']."' ");
  $d = mysqli_fetch_array($driver);
  
  $sql = mysqli_query($conn, "SELECT * FROM driverpost where username = '".$_SESSION['username']."'");
  $arr1 = mysqli_fetch_array($sql);

  if($d['booking_status'] == NULL){
    if(mysqli_num_rows($sql)){ ?>
      <div class="jumbotron">
        <img src="upload/<?php echo $arr1["truck_image"]?>" alt="truck_img" height="auto" width="device-width">
      </div>
      <div class="container unique">
        <div class="row">
          <div class="col-sm">
            <h5>Driver Name: <?php echo $arr1["name"]?></h5>
          </div>
          <div class="col-sm">
            <h5>City: <?php echo $arr1["city"]?></h5>
          </div>
          <div class="col-sm">
            <h5>Price: <?php echo $arr1["base_price"]?></h5>
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
            <h5>Contact: <?php echo $arr["contact"]?></h5>
          </div>
          <div class="col-sm">
            <h5>Truck Model: <?php echo $arr["type_of_truck"]?></h5>
          </div>
          <div class="col-sm">
            <h5>Truck Number: <?php echo $arr["vehicle_number"]?></h5>
          </div>
        </div>

        <div class="row">
          <div class="col-md">
            <button class="btn btn-primary" style="width: 100%" data-target="#exampleModalCenter1" data-toggle="modal">Edit</button>
          </div>
          <div class="col-md">
            <button class="btn btn-secondary" style="width: 100%"><a href="delete.php" style="text-decoration: none; color: white;"> Delete</a></button>
          </div>
        </div>
      </div>
  <?php
    }else{ ?>
      <div class="container" style="display:flex; justify-content:center; align-items:center">
      <div style="position:absolute; top:50%">
      <h5>No Adds yet, Post One.</h5> 
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" style="margin-left: 40px;">
        Post Ads
      </button>
      </div>
      </div>
  <?php  }
  }else if($d['booking_status'] == 1){
    $sql2 = mysqli_query($conn, "SELECT * FROM booking_details WHERE driver= '".$_SESSION['username']."'");
    if(mysqli_num_rows($sql2)){
      $arr2 = mysqli_fetch_array($sql2); 
      $username = $arr2['client'];
      $sql3 = mysqli_query($conn, "SELECT * from users WHERE username = '$username' ");
      $arr3 = mysqli_fetch_array($sql3);
  ?>
  <div class="container">
    <h2>Your Order</h2>
    <br><br><br>
    <div class="row">
      <div class="col">
          <h3 style="text-indent :8em;"><?php echo "Client Name:";?></h3>
        </div>
        <div class="col">
          <h3><?php echo $arr3['name']?></h3>
        </div> 
    </div>
    <br>
    <div class="row">
      <div class="col">
        <h3 style="text-indent :8em;"><?php echo "Contact: "?></h3>
      </div>
      <div class="col">
        <h3><?php echo $arr3['contact']?></h3>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">
        <h3 style="text-indent :8em;"><?php echo "Source: "?></h3>
      </div>
      <div class="col">
        <h3><?php echo $arr2['source']?></h3>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">
        <h3 style="text-indent :8em;"><?php echo "Destination: "?></h3>
      </div>
      <div class="col">
        <h3><?php echo $arr2['destination']?></h3>
      </div>
    </div>
    <br>
    <div class="row">
    <div class="col">
        <h3 style="text-indent :8em;"><?php echo "Distance: "?></h3>
      </div>
      <div class="col">
        <h3><?php echo $arr2['distance']."km";?></h3>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col">
        <h3 style="text-indent :8em;"><?php echo "Earning: "?></h3>
      </div>
      <div class="col">
        <h3><?php echo "₹".$arr2['total']?></h3>
      </div>
    </div>
    <br><br><br>
    
      
    <?php 
      if(mysqli_num_rows($sql)){
        mysqli_query($conn, "DELETE FROM driverpost where username = '".$_SESSION['username']."' ");
      }
    ?>
    
  </div>
  <?php  }
  }
?>


<div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Advertisement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col">
                <label for="nameinput">Name</label>
                <input type="text" class="form-control" name="name1" id="nameinput" placeholder="Driver Name" value="<?php echo $arr1["name"]?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="cityinput">City</label>
                <input type="text" class="form-control" name="city" id="cityinput" placeholder="Enter City" value="<?php echo $arr1["city"]?>">
              </div>
              <div class="form-group col-md-6">
                <label for="minimumprice">Base Price</label>
                <input type="number" class="form-control" name="price" id="minimumprice" placeholder="Enter Minimum Charges" value="<?php echo $arr1["base_price"]?>">
              </div>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="truck" name="truck_image" >
                <label class="custom-file-label" for="truck">Truck Image</label>
              </div>
            </div><br>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>