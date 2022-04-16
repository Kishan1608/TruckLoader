<?php
//This script will handle login.
session_start();

//Check if user is already logged in
if(isset($_SESSION['username'])){
    header("location: welcome.php");
    exit;
}
require_once "config.php";
$username = $password = "";
$err = "";

//if request method is post
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
        $err = "Enter Username And Password";
    }else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

    if(empty($err)){
        $sql = "SELECT id, username, password from users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        //Trying to execute this statement
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashed_password)){
                        //this means the password is correct allow user to login
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $id;
                        $_SESSION["loggedin"] = true;
                        
                        //Redirect user to welcome page
                        header("location: welcome.php");
                    }
                }
            }
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
    <title>Login TruckLoader</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
    
      <a class="navbar-brand" href="index.html">TruckLoader</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li> 
        </ul>
      </div>
      
    
  </nav>
<br><br><br><br>
<div class="container">
    <h4>Login Here: </h4><hr>
    <form action="" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Username</label>
                <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Enter UserName">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Password</label>
                <input type="password" class="form-control" name ="password" id="inputPassword4" placeholder="Enter Password">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form><br>
    <small>Didn't have an account? Create One.</small>
    <a href="register.php"><button class="btn btn-primary">Create Account</button></a>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>