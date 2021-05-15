<?php
  session_start();
  include_once "databaseConn.php";
  $loginUserid=$loginUser="";
  if(isset($_SESSION["loginUserid"])){
    $loginUserid = $_SESSION["loginUserid"];
    $sqlForUser = "SELECT * FROM Users WHERE User_id = ".$loginUserid;
    $resultFound = $connection->query($sqlForUser);
    if($resultFound->num_rows>0){
      $loginUser = $resultFound->fetch_assoc();
    } 
  }
  $error = false;
  $cityErr = $startDateErr = $endDateErr = $guestErr = "";
  $city = $startDate = $endDate = $guestNo = "";
  if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty($_POST["City"])||$_POST["City"]==" "){
      $error = true;
      $cityErr = "Please enter the city.";
    }else{
      $city = $_POST["City"];
    }
    if(empty($_POST["CheckInDate"])){
      $error = true;
      $startDateErr = "Please select the Check in Date.";
    }else{
      $startDate = $_POST["CheckInDate"];
    }
    if(empty($_POST["CheckOutDate"])){
      $error = true;
      $endDateErr = "Please select the Check out Date.";
    }else{
      $endDate = $_POST["CheckOutDate"];
    }
    if(empty($_POST["GuestNo"])){
      $error = true;
      $guestErr = "Please enter the guest number.";
    }else{
      $guestNo = $_POST["GuestNo"];
    }
  }
  echo $city;
  echo $startDate;
  echo $endDate;
  echo $guestNo;
  if(isset($_POST["City"], $_POST["CheckInDate"], $_POST["CheckOutDate"], $_POST["GuestNo"])&&$error==false){
    $_SESSION["CitySearch"]=$city;
    $_SESSION["CheckInDate"]=$startDate;
    $_SESSION["CheckOutDate"]=$endDate;
    $_SESSION["GuestNoSearch"]=$guestNo;
    header("Location: searchResult.php");
    exit();
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
    <script>
      /*
      **use this function to check if the checkout date is after the checkin date 
      */
        function dateComp(){
            var checkin=new Date(document.getElementById("Check in date").value);
        console.log(checkin);
        var checkout=new Date(document.getElementById("Check out date").value);
        console.log(checkout);
        var error=document.getElementById("error");
            if(checkin>=checkout){
                error.style.display="inline-block";
            }else{
                error.style.display="none";
            }
        }
    </script>       
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

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <br><br><br><br>
    <span style="display: none; color:darkred" id="error">Check out date must be after check in date</span>
    <input type="text"  class="center" id="City" name="City" placeholder="Enter the destination city"><br>
    <span class="error"><?php echo $cityErr;?></span><br>
    <!--the date picker is not included in Safari, Plz use Chrome-->    
    <input type="date" class="date" id="Check in date" name="CheckInDate" placeholder="Check in date" onchange="dateComp()">
    <input type="date" class="date" id="Check out date"  name="CheckOutDate" placeholder="Check out date" onchange="dateComp()">   
    <input type="number" class="dateGuest" id="NumGuest" name="GuestNo" placeholder="Guest Number" min="1"><br>
    <span class="error dateErr"><?php echo $startDateErr;?></span>
    <span class="error dateErr"><?php echo $endDateErr;?></span>
    <span class="error guestErr"><?php echo $guestErr;?></span>
    <br>  
    <input type="submit" class="center" value="Search"> <br>
  </form>
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














