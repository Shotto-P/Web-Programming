<?php
    session_start();

    include_once "databaseConn.php";
    $error=false;
    $emailErr=$pwdErr="";
    $email=$pwd="";
    $checkResult = $user = "";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["email"])||$_POST["email"]==" "){
            $error=true;
            $emailErr="Please enter the email.";
        }else{
            $sqlForCheck = "SELECT * FROM Users WHERE Email = '".$_POST["email"]."'";
            $checkResult = $connection->query($sqlForCheck);
            if($checkResult->num_rows == 0){
                $error=true;
                $emailErr="This email is not registered.";
            }else{
                $email = $_POST["email"];
            }
        }
        if(empty($_POST["password"])){
            $error=true;
            $pwdErr="Please enter the password.";
        }else{
            if($checkResult->num_rows > 0){
                $user=$checkResult->fetch_assoc();
                if($user["Password"]!=$_POST["password"]){
                    $error=true;
                    $pwdErr="Wrong password!!";
                    
                }else{
                    $pwd=$_POST["password"];
                }
            }
        }

        if(isset($_POST["email"], $_POST["password"])&&$error==false){
            $_SESSION["loginUserid"] = $user["User_id"];
            if($user["Email"]=="test@gmail.com"){
                header("Location: admin.php");
            }else{
                if($user["ABN"]==0){
                    header("Location: home.php");
                }else{
                    header("Location: host.php");
                }
            }
            
            exit();
        }
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
</head>
    
   
<body>    
  <div class="topBar">
    <div class="barItem">
        <h3 class="brand">UniBnB</h3>
    </div>
    <div class="barItem iconDiv">
        <i class="fas fa-user-alt dropdown-toggle" data-toggle="dropdown" style="font-size: 24px;"></i>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Login</a>
            <a class="dropdown-item" href="registration.php">Create Account</a>
        </div>
    </div>
  </div>
    <div class="loginForm">
        <h3 style="text-align: center;">Login</h3>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label class="formLabel">Email Address:</label>
            <input type="text" name="email" id="email">
            <span class="error"><?php echo $emailErr; ?></span><br><br>
            <label class="formLabel">Password:</label>
            <input type="password" name="password" id="password">
            <span class="error"><?php echo $pwdErr; ?></span><br>
            <a href="registration.php">Do not have account?</a>
            <br>
            <input type="submit" name="login" class="btn btn-outline-info loginBtn" value="Login"><br>
        </form>
        <br><br><br><br>
    </div>
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
