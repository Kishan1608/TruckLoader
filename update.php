<?php 
require_once "config.php";
session_start();

if(isset($_POST["submit"])){
    $price = $_POST['price'];
    $city = $_POST['city'];
    $image = $_FILES['truck_image'];
    $username = $_SESSION['username'];
    $name = $_POST['name1'];

    echo $price;

    $fileName = $_FILES['truck_image']['name'];
    $fileError = $_FILES['truck_image']['error'];
    $fileTemp = $_FILES['truck_image']['tmp_name'];
    $fileSize = $_FILES['truck_image']['size'];
    $fileType = $_FILES['truck_image']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('png','jpg', 'jpeg');
    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
          if($fileSize < 300000){
            $fileNameNew = uniqid('IMG-', true).".".$fileActualExt;
            $fileDEstination = 'upload/'.$fileNameNew;
            move_uploaded_file($fileTemp, $fileDEstination);
  
            $sql = "UPDATE driverpost SET truck_image = '$fileNameNew', base_price = '$price', city = '$city', name = '$name' WHERE username = '$username' ";
                if(mysqli_query($conn, $sql)){
                    header("Location: driverWelcome.php");
                }else{
                    echo "Error Updating";
                }
            }else{
                echo "Your File is too big ";
            }
        }else{
            echo "Error in File";
        }
    }else{
        echo "You cannot upload file of this type. ";
    }
}

echo "Update Page";

?>