<?php
    session_start();
    include_once "databaseConn.php";
    $loginUserid=$loginUser=$selectedAccom="";
    if(isset($_SESSION["loginUserid"])){
        $loginUserid = $_SESSION["loginUserid"];
        $sqlForUser = "SELECT * FROM Users WHERE User_id = ".$loginUserid;
        $resultFound = $connection->query($sqlForUser);
        if($resultFound->num_rows>0){
            $loginUser = $resultFound->fetch_assoc();
        } 
    }
    $selectedAccomid = $_SESSION["selectedAccomid"];
    $sqlForAccom = "SELECT * FROM Accommodations WHERE Accom_id = ".$selectedAccomid;
    $accomFound = $connection->query($sqlForAccom);
    if($accomFound->num_rows>0){
        $selectedAccom = $accomFound->fetch_assoc();
    }
    $status = "Pending";
    $stmt = $connection->prepare("INSERT INTO Bookings (Booking_status, Accom_id, StartDate, EndDate, Tenant_id, TotalPrice) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissii", $status, $selectedAccomid, $_SESSION["CheckInDate"], $_SESSION["CheckOutDate"], $loginUserid, $_SESSION["TotalPrice"]);
    if($stmt->execute()){
       // echo "Successfully";
    }else{
        echo $stmt->error;
    }
    $stmt->close();
    $connection->close();
 ?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
        <meta name="viewpoint" content="width=device-width, initial-scale=1">
        <title>UniBnB</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/6c3fa9686c.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="script.js"></script>
    <style>
        body {
              font-family: Arial, Helvetica, sans-serif;
            }
        * {
            margin: 0;
            padding: 0;
            list-style: none;
          }
    </style>      
</head>
    
   
<body>    
    <div class="topBar">
        <div class="barItem">
            <h3 class="brand">UniBnB</h3>
        </div>
        <div class="barItem iconDiv">
            <span style="font-weight: bold;"><?php if(isset($_SESSION["loginUserid"])){echo $loginUser["Fname"];}?></span>
            <i class="fas fa-user-alt dropdown-toggle" data-toggle="dropdown" style="font-size: 24px;"></i>
            <div class="dropdown-menu">
                <?php if(!isset($_SESSION["loginUserid"])){?>
                <a class="dropdown-item" href="login.php">Login</a>
                <a class="dropdown-item" href="registration.php">Create Account</a>
                <?php }else{ ?>
                        <?php
                            if($loginUser["ABN"]==0){ ?>
                                <a class="dropdown-item" href="profile.php">Profile Page</a>
                            <?php }else{ ?>
                                <a class="dropdown-item" href="host.php">Profile Page</a>
                            <?php }
                        ?>
                    <a class="dropdown-item" href="logout.php">Log Out</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div>
        <h2 style="text-align: center; font-weight: bold; margin-top: 20px;">Your Request has been successfully sent to the host!</h2>
        <br>
        <span class="textRequestSent">Want to check your request? <a href="profile.php" class="btn btn-outline-info requestSentBtn">Go to Profile Page</a></span>
    </div>
    <br><br><br><br>
    
  <div class="row"> 
    <div class="col-md-4">
      <img src="img/entireHouse.jpg" alt="Entire House" class="img-rounded" width="360px" height="360px">
    </div>
  
    <div class="col-md-4">
      <img src="img/privateRoom.jpg" alt="Private Room" class="img-rounded" width="360px" height="360px">
    </div>

    <div class="col-md-4">
      <img src="img/cottage.jpg" alt="Cottage" class="img-rounded" width="360px" height="360px">
    </div>
     
  </div>    
    
</body>
</html>














