<?php
require_once "config.php";

$username = $password = $confirm_password = $name = $contact = $typeoftruck = $drivinglicence = $vehiclenumber = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            mysqli_stmt_bind_param($stmt, "s", $param_contact);
            mysqli_stmt_bind_param($stmt, "s", $param_typeoftruck);
            mysqli_stmt_bind_param($stmt, "s", $drivinglicence);
            mysqli_stmt_bind_param($stmt, "s", $vehiclenumber);

            // Set the value of param username
            $param_username = trim($_POST['username']);
            $param_name = trim($_POST['name']);
            $param_contact = $_POST['contact'];
            $param_typeoftruck = trim($_POST['typeoftruck']);
            $param_drivinglicence = trim($_POST['driving_licence']);
            $param_vehiclenumber = trim($_POST['vehicle_number']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                    $name = trim($_POST['name']);
                    $contact = $_POST['contact'];
                    $typeoftruck = trim($_POST['typeoftruck']);
                    $drivinglicence = trim($_POST['driving_licence']);
                    $vehiclenumber = trim($_POST['vehicle_number']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO drivers (name, username, password, contact, type_of_truck, driving_licence, vehicle_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "sssssss", $param_name, $param_username, $param_password, $param_contact, $param_typeoftruck, $param_drivinglicence, $param_vehiclenumber);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $param_name = $name;
        $param_contact = $contact;
        $param_typeoftruck = $typeoftruck;
        $param_drivinglicence = $drivinglicence;
        $param_vehiclenumber = $vehiclenumber;

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: driverLogin.php");
            exit();
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
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
    <title>Register TruckLoader</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.html">TruckLoader</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="driverLogin.php">Login</a>
      </li>
      
    </ul>
  </div>
</nav>

<br><br><br><br>
<div class="container">
    <h4>Register Here: </h4><hr>
    <form action="" method="post">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail5">Name</label>
      <input type="text" class="form-control" name="name" id="inputEmail5" placeholder="Enter Your Name">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Username</label>
      <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Email">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="password" class="form-control" name ="password" id="inputPassword4" placeholder="Password">
    </div>
  
    <div class="form-group col-md-6">
      <label for="inputPassword4">Confirm Password</label>
      <input type="password" class="form-control" name ="confirm_password" id="inputPassword" placeholder="Confirm Password">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Contact</label>
      <input type="tel" pattern="[0-9]{10}" class="form-control" name="contact" id="inputEmail4" placeholder="Enter Your Contact Number" >
    </div>
    <div class="form-group col-md-6">
        <label for="inputEmail4">Type of Truck</label>
        <select class="form-select" aria-label="Default select example" name="typeoftruck" id="typeoftruck">
            <option value="TATA ACE" selected>TATA ACE</option>
            <option value="TATA 407">TATA 407</option>
            <option value="TATA 12 Feet">TATA 12 Feet</option>
            <option value="Canter 14 Feet">Canter 14 Feet</option>
            <option value="LPT 17 Feet">LPT 17 Feet</option>
            <option value="1109 -19Feet">1109 -19Feet</option>
            <option value="LPT 18 Feet">LPT 18 Feet</option>
            <option value="Taurus 22 Feet">Taurus 22 Feet</option>
            <option value="Taurus 24-25 Feet">Taurus 24-25 Feet</option>
            <option value="Taurus 25-26 Feet">Taurus 25-26 Feet</option>
            <option value="ASHOK LEYLAND DOST">ASHOK LEYLAND DOST</option>
            <option value="MAHINDRA BOLERO">MAHINDRA BOLERO</option>
            <option value="TATA 22 Feet">TATA 22 Feet</option>
            <option value="CONTAINER 32 FT SXL">CONTAINER 32 FT SXL</option>
            <option value="CONTAINER 32 FT MXL">CONTAINER 32 FT MXL</option>
            <option value="20 Feet Open All Side">20 Feet Open All Side</option>
            <option value="20′ Container">20′ Container</option>
            <option value="24′ Container">24′ Container</option>
            <option value="28′ Container">28′ Container</option>
            <option value="32′ Container">32′ Container</option>
            <option value="34′ Container">34′ Container</option>
            <option value="40′ Container">40′ Container</option>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Driving Licence</label>
      <input type="text" class="form-control" name="driving_licence" id="inputEmail4" aria-describedby="DL" placeholder="Eg:MH - 14 - 2011 - 0062821">
      <small id="DL" class="form-text text-muted">We'll never share your information with anyone else.</small>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Vehicle Number</label>
      <input type="text" class="form-control" name="vehicle_number" id="inputEmail4" placeholder="Eg: MH 47 AL 4517">
    </div>
  </div>
  <div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>
  </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>