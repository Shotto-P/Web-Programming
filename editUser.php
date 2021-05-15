<?php
    session_start();
    include_once "databaseConn.php";
    $loginUserid=$loginUser=$userid=$user="";
    if(isset($_SESSION["loginUserid"])){
        $loginUserid = $_SESSION["loginUserid"];
        $sqlForUser = "SELECT * FROM Users WHERE User_id = ".$loginUserid;
        $resultFound = $connection->query($sqlForUser);
        if($resultFound->num_rows>0){
            $loginUser = $resultFound->fetch_assoc();
        } 
    }
    $userid=$_POST["editUserid"];
    $sqlForEditUser="SELECT * FROM Users WHERE User_id = ".$userid;
    $EditUserResult=$connection->query($sqlForEditUser);
    if($EditUserResult->num_rows>0){
        $user=$EditUserResult->fetch_assoc();
    }

    $fname=$lname=$email=$mobile=$userpassword=$address=$country=$abn="";
    $fnameErr=$lnameErr=$emailErr=$mobileErr=$passwordErr=$addressErr=$countryErr=$abnErr="";
    $error=false;

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["form_number"])){
            if($_POST["form_number"]==$loginUserid){
                if(empty($_POST["fname"])||$_POST["fname"]==" "){
                    $error=true;
                    $fnameErr="Please enter the first name.";
                }else{
                    $fname = $_POST["fname"];
                }
                if(empty($_POST["lname"])||$_POST["lname"]==" "){
                    $error=true;
                    $lnameErr="Please enter the last name.";
                }else{
                    $lname = $_POST["lname"];
                }
                if(empty($_POST["email"])||$_POST["email"]==" "){
                    $error=true;
                    $emailErr="Please enter the email.";
                }else{
                    $email = $_POST["email"];
                }
                if(empty($_POST["mobile"])){
                    $error=true;
                    $mobileErr="Please enter the mobile number.";
                }else{
                    $mobile = $_POST["mobile"];
                }
                if(empty($_POST["password"])||empty($_POST["confirmation"])){
                    $passwordErr="* Please check password";
                    $error=true;
                }else{
                    if($_POST["password"]!=$_POST["confirmation"]){
                        $passwordErr="* Password Don't Match";
                        $error=true;
                    }else{
                        $userpassword = $_POST["password"];
                    }
                }
                if(empty($_POST["address"])||$_POST["address"]==" "){
                    $error=true;
                    $addressErr="Please enter the address.";
                }else{
                    $address = $_POST["address"];
                }
                if(empty($_POST["country"])||$_POST["country"]==" "){
                    $error=true;
                    $countryErr="Please enter the country.";
                }else{
                    $country = $_POST["country"];
                }
                if(isset($_POST["abn"])){
                    if(empty($_POST["abn"])){
                        $error=true;
                        $abnErr="Please enter the ABN number";
                    }else{
                        $abn =$_POST["abn"];
                    }
                } 
        
                if(isset($_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["mobile"], $_POST["password"], $_POST["confirmation"], $_POST["address"], $_POST["country"])&&$error==false){
                    $_SESSION["fname"]=$fname;
                    $_SESSION["lname"]=$lname;
                    $_SESSION["email"]=$email;
                    $_SESSION["mobile"]=$mobile;
                    $_SESSION["password"]=$userpassword;
                    $_SESSION["address"]=$address;
                    $_SESSION["country"]=$country;
                    
                    if(isset($_POST["abn"])){
                        $sqlForUpdate = "UPDATE Users SET Fname = '".$fname."', Lname = '".$lname."', Email = '".
                        $email."', Mobile = ".$mobile.", Password = '".$userpassword."', Address = '".$address."', Country = '".
                        $country."', ABN = ".$abn." WHERE User_id = ".$userid;
                    }else{
                        $sqlForUpdate = "UPDATE Users SET Fname = '".$fname."', Lname = '".$lname."', Email = '".
                        $email."', Mobile = ".$mobile.", Password = '".$userpassword."', Address = '".$address."', Country = '".
                        $country." WHERE User_id = ".$userid;
                    }
                    
                    if($connection->query($sqlForUpdate)===TRUE){
                        echo "Record updated successfully";
                    }else{
                        echo "Error updating record: ".$connection->error;
                    }
        
                    header("Location: admin.php");
                }
            }
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
                    <a class="dropdown-item" href="admin.php">Profile Page</a>
                    <a class="dropdown-item" href="logout.php">Log Out</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div>
    <br>
                    <div>
                        <h3 class="AccDBBar">Manage User Profile</h3>
                        <button type="submit" form="editform" class="AccDBBar btn btn-outline-info btnNewUser">Submit Change</button>
                    </div>
                    <div>
                        <form id="editform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                            <input type="hidden" name="form_number" value="<?php echo $user["User_id"];?>">
                            <div class="profileEditForm">
                                <div class="editFormItem">
                                    <label class="reserveformLabel">First Name</label><br>
                                    <input type="text" name="fname" class="editForm" value="<?php echo $user["Fname"];?>"><br>
                                    <span class="error"><?php echo $fnameErr;?></span>
                                </div>
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Last Name</label><br>
                                    <input type="text" name="lname" class="editForm" value="<?php echo $user["Lname"];?>"><br>
                                    <span class="error"><?php echo $lnameErr;?></span>
                                </div>
                            </div>
                            <div class="profileEditForm">
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Email Address</label><br>
                                    <input type="email" name="email" class="editForm" value="<?php echo $user["Email"]; ?>" readonly><br>
                                    <span class="error"><?php echo $emailErr;?></span>
                                </div>
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Mobile Number</label><br>
                                    <input type="number" name="mobile" class="editForm" value="<?php echo $user["Mobile"];?>"><br>
                                    <span class="error"><?php echo $mobileErr;?></span>
                                </div>
                            </div>
                            <div class="profileEditForm">
                                <div class="editFormItem">
                                    <label class="reserveformLabel">New Password</label><br>
                                    <input type="password" name="password" class="editForm" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[!@#$%^&*+`~'=?\|\]\[\(\)\->/]).{6,12}" placeholder="Enter the new password"><br>
                                    <span class="error"><?php echo $passwordErr;?></span>
                                </div>
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Confirm Password</label><br>
                                    <input type="password" name="confirmation" class="editForm" placeholder="Confirm Your Password"><br>
                                    <span class="error"><?php echo $passwordErr;?></span>
                                </div>
                            </div>
                            <div class="profileEditForm">
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Address</label><br>
                                    <input type="text" name="address" class="editForm" value="<?php echo $user["Address"]; ?>"><br>
                                    <span class="error"><?php echo $addressErr;?></span>
                                </div>
                                <div class="editFormItem">
                                    <label class="reserveformLabel">Country</label><br>
                                    <input type="text" name="country" class="editForm" value="<?php echo $user["Country"]; ?>"><br>
                                    <span class="error"><?php echo $countryErr;?></span>
                                </div>
                            </div>
                            <?php if($user["ABN"]!=0){?>
                                <div class="profileEditForm">
                                    <div class="editFormItem">
                                        <label class="reserveformLabel">ABN</label><br>
                                        <input type="number" name="abn" class="editForm" value="<?php echo $user["ABN"]; ?>"><br>
                                        <span class="error"><?php echo $abnErr;?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
 
</body>
</html>














