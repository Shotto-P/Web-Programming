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
    $selectedAccomid = $_POST["selectedAccomid"];
    $_SESSION["selectedAccomid"] = $selectedAccomid;
    $sqlForAccom = "SELECT * FROM Accommodations WHERE Accom_id = ".$selectedAccomid;
    $accomFound = $connection->query($sqlForAccom);
    if($accomFound->num_rows>0){
        $selectedAccom = $accomFound->fetch_assoc();
    }
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
    <div style="display: flex;">
    <div class="tableDiv">
        <h3>Your Reservation</h3>
        <table>
             <tr>
                 <th>Selected Accommodation</th>
                 <td><?php echo $selectedAccom["Title"];?></td>
             </tr>
             <tr>
                 <th>Check In</th>
                 <td><?php echo $_SESSION["CheckInDate"];?></td>
             </tr> 
             <tr>
                 <th>Check Out</th>
                 <td><?php echo $_SESSION["CheckOutDate"];?></td>
             </tr> 
             <tr>
                 <th>Guest(s)</th>
                 <td><?php echo $_SESSION["GuestNoSearch"];?></td>
             </tr>  
             <tr>
                 <th>Night Stay(s)</th>
                 <td><?php
                 $checkout = new DateTime($_SESSION["CheckOutDate"]);
                 $checkin = new DateTime($_SESSION["CheckInDate"]);
                 $difference = $checkout->diff($checkin)->format("%a");
                 echo $difference;
                 ?></td>
             </tr>
             <tr>
                 <th>Total Price</th>
                 <td>$ <?php 
                 $total = $difference * $selectedAccom["Price"];
                 $_SESSION["TotalPrice"] = $total;
                 echo $total;
                 ?></td>
             </tr>        
        </table>
    </div>
    <div class="formDiv">
        <h3>Guest details</h3>
        <form action="requestSent.php">
            <label class="reserveformLabel">First Name:</label><br>
            <input type="text" class="reserverformInput" value="<?php echo $loginUser["Fname"];?>" readonly><br>
            <label class="reserveformLabel">Last Name:</label><br>
            <input type="text" class="reserverformInput" value="<?php echo $loginUser["Lname"];?>" readonly><br>
            <label class="reserveformLabel">Email Address:</label><br>
            <input type="email" class="reserverformInput" value="<?php echo $loginUser["Email"];?>" readonly><br>
            <label class="reserveformLabel">Mobile:</label><br>
            <input type="number" class="reserverformInput" value="<?php echo $loginUser["Mobile"];?>" readonly><br>
            <br>
            <a href="searchResult.php" class="btn btn-outline-info cancelBtn">Cancel</a>
            <input type="submit" value="Comfirm" class="btn btn-outline-info confirmBtn">
        </form>
    </div>
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














