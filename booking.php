<?php 
require_once "config.php";
require('fpdf184/fpdf.php');
//$db = new PDO('mysql:localhost; dbname=truckloader', 'root','');
session_start();

$sql = mysqli_query($conn,"SELECT * FROM booking_details WHERE client = '".$_SESSION['username']."' ");
$arr1 = mysqli_fetch_array($sql);

$sql1 = mysqli_query($conn,"SELECT * FROM users WHERE username = '".$_SESSION['username']."'");
$arr = mysqli_fetch_array($sql1);

if(array_key_exists('button1', $_POST)){
    class PDF extends FPDF {
  
        // Page header
        function Header() {
              
            // Add logo to page
            $this->Image('upload\truck-logo-cargo-logo-delivery-cargo-trucks-log-vector-26486067.jpg',10,8,33);
              
            // Set font family to Arial bold 
            $this->SetFont('Arial','B',20);
              
              
            // Header
            $this->Cell(210,5,'TruckLoader',0,0,'C');
              
            // Line break
            $this->Ln(8);

            $this->SetFont('Times','',12);
            $this->Cell(210,2,'VESIT Logistics',0,0,'C');
            $this->Ln(20);
        }
      
        // Page footer
        function Footer() {
              
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
              
            // Arial italic 8
            $this->SetFont('Arial','I',8);
              
            // Page number
            $this->Cell(0,10,'Page ' . 
                $this->PageNo() . '/{nb}',0,0,'C');
        }

        function headerTable(){
            $this->SetFont('Times','B',12);
            
            $this->Cell(45,10,'Source',1,0,'C');
            $this->Cell(45,10,'Destination',1,0,'C');
            $this->Cell(45,10,'Distance',1,0,'C');
            $this->Cell(45,10,'Total',1,0,'C');
            $this->Ln();
        }   

        // function viewTable($db){
        //     $this->SetFont('Times','',12);
        //     $stmt = $db->query("SELECT * FROM booking_details WHERE username = '".$_SESSION['username']."' ");
        //     while($data=$stmt->fetch(PDO::FETCH_OBJ)){
        //         $this->Cell(38,10,$data->source,1,0,'L');
        //         $this->Cell(38,10,$data->destination,1,0,'L');
        //         $this->Cell(38,10,$data->distance,1,0,'C');
        //         $this->Cell(38,10,$data->total,1,0,'C');
        //         $this->Cell(38,10,$data->payment_status,1,0,'C');
        //         $this->Ln();
        //     }
            
        // }
    }
      
    //Instantiation of FPDF class
    $pdf = new PDF();
    // Define alias for number of pages
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->headerTable();
    $pdf->Cell(45,100,$arr1['source'],1,0,'C');
    $pdf->Cell(45,100,$arr1['destination'],1,0,'C');
    $pdf->Cell(45,100,$arr1['distance'].'km',1,0,'C');
    $pdf->Cell(45,100,'',1,0,'C');

    $pdf->Ln();
    $pdf->Cell(135,30,'GST TAX',1,0,'C');
    $pdf->Cell(45,10,'CGST 9%',1,0,'C');
    $pdf->Ln();
    $pdf->Cell(135,10,'',0,0,'C');
    $pdf->Cell(45,10,'SGST 9%',1,0,'C');
    $pdf->Ln();
    $pdf->Cell(135,10,'',0,0,'C');
    $pdf->Cell(45,10,'Rs'.$arr1['total'],1,0,'C');
    $pdf->Output();
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

                $sql = mysqli_query($conn,"SELECT * FROM booking_details WHERE client = '".$_SESSION['username']."' ");
                $arr3 = mysqli_fetch_array($sql);
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
                    <a href="cancel.php?driver=<?php echo $a['driver'] ?>" class="btn btn-danger"> Cancel Truck</a><br><br>
                    <form action=""  method="POST">
                        <button class="btn btn-success" name="button1">Get Receipt</button>
                    </form>
                </div>
            </div>
            <br>
        <?php endforeach?>
    </div>

</body>
</html>
