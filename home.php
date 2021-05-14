<?php
  $servername = "localhost";
  $username = "root";
  $password = "pangxiaotao";

  $connection = new mysqli($servername, $username, $password);
  if (!$connection) {
      die("can't connect".mysqli_error());
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
        <i class="fas fa-user-alt dropdown-toggle" data-toggle="dropdown" style="font-size: 24px;"></i>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="login.php">Login</a>
            <a class="dropdown-item" href="registration.php">Create Account</a>
        </div>
    </div>
  </div>
 
  <form action="searchResult.php" method="POST">
    <br><br><br><br>
    <span style="display: none; color:darkred" id="error">Check out date must be after check in date</span>
    <input type="text"  class="center" id="City" name="City" placeholder="Enter the destination city" required><br><br>

    <!--the date picker is not included in Safari, Plz use Chrome-->    
    <input type="date" class="date" id="Check in date" name="Check in date" placeholder="Check in date" onchange="dateComp()" required>

    <input type="date" class="date" id="Check out date"  name="Check out date" placeholder="Check out date" onchange="dateComp()" required >
       
    <input type="number" class="dateGuest" id="NumGuest" name="NumGuest" placeholder="Guest Number" min="1" required><br><br>
       
    <input type="submit" class="center" value="Search"> <br>
  </form>
  <br><br><br><br>
    
  <div class="row"> 
    <div class="col-md-4">
      <img src="img/entire house.jpg" alt="Entire House" class="img-rounded" width="360px" height="360px">
    </div>
  
    <div class="col-md-4">
      <img src="img/private room.jpg" alt="Private Room" class="img-rounded" width="360px" height="360px">
    </div>

    <div class="col-md-4">
      <img src="img/cottage.jpg" alt="Cottage" class="img-rounded" width="360px" height="360px">
    </div>
     
  </div>    
    
</body>
</html>














