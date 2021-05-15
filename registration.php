<?php
  session_start();

  include_once "databaseConn.php";
  $error=false;
  $fnameErr=$lnameErr=$emailErr=$mobileErr=$passwordErr=$addressErr=$countryErr=$abnErr=$avatarErr="";
  $fname=$lname=$email=$mobile=$userpassword=$address=$country=$abn="";
  if($_SERVER["REQUEST_METHOD"]=="POST"){
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
      $sqlForCheck = "SELECT * FROM Users WHERE Email = '".$_POST["email"]."'";
      $checkResult = $connection->query($sqlForCheck);
      
      if($checkResult->num_rows > 0){
        $error=true;
        $emailErr = "Email already been used!!";
      }else{
        $email = $_POST["email"];
      }
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
    if($_POST["usertype"]=="1"){
      if(empty($_POST["abn"])){
        $error=true;
        $abnErr="Please enter the ABN number.";
      }else{
        $abn=$_POST["abn"];
      }
    }else{
      $abn="";
    }
    if(empty($_FILES["avatar"]["name"])){
      $avatarErr = "* avatar is required!";
      $error = true;
    }else{  
      //manage the upload image
      $fileName = basename($_FILES["avatar"]["name"]);
      $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      $allowTypes = array('jpg', 'png', 'jpeg');
      if(in_array($fileType, $allowTypes)){
        $image = $_FILES["avatar"]["tmp_name"];
        $imgContent = file_get_contents($image);
      }else{
        $imageErr = "image file type need to be one of them: jpg, jpeg, png";
        $error=true;
      }
    }
  }

  if(isset($_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["mobile"], $_POST["password"], $_POST["confirmation"], $_POST["address"], $_POST["country"], $_POST["abn"], $_FILES["avatar"]["name"])&&$error==false){
    $_SESSION["fname"]=$fname;
    $_SESSION["lname"]=$lname;
    $_SESSION["email"]=$email;
    $_SESSION["mobile"]=$mobile;
    $_SESSION["password"]=$userpassword;
    $_SESSION["address"]=$address;
    $_SESSION["country"]=$country;
    $_SESSION["abn"]=$abn;

    $stmt = $connection->prepare("INSERT INTO Users (Fname, Lname, Email, Mobile, Password, Address, Country, ABN, Avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisssis", $fname, $lname, $email, $mobile, $userpassword, $address, $country, $abn, $imgContent);
        if($stmt->execute()){
            //echo "Successful!";
        }else{
            echo $stmt->error;
        }
        $stmt->close();
        $connection->close();
        header("Location: home.php");
        exit();
  }
?>
<!DOCTYPE html>
<html>
    <!-- Headings -->
    <head>
        <meta charset="UTF-8">
        <title>UniBnB</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/6c3fa9686c.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="script.js"></script>
    </head>
    <!-- Body -->

    <style>
      .mainDiv {
        background-image: url("img/regi2.jpeg");
        background-size: 100%;
        
        background-repeat: no-repeat;
      }
    </style>
    <body>
        <div class="topBar">
            <div class="barItem">
                <h3 class="brand">UniBnB</h3>
            </div>
        </div> 
        <div class="mainDiv">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                 <br>
                 <br>
               <h2><p>CREATE ACCOUNT</p></h2>
               <br>
               <h4><p style="margin-left:10%">Are you registering as a client or host?</p></h4>
               <select name="usertype"style="width: 76%;height:50Px;margin-left: 10%;border-radius: 5px;" id="AAA" onchange="gradeChange()">
				<option value="1" style="font-size: 16px;font-weight: 200;background-color: dimgrey;height: 50px;">Host</option>
				<option value="2" style="font-size: 16px;font-weight: 200;background-color: dimgrey;height: 50px;">Client</option>
			</select>  
            <br>
            <br>
        <div style="width:80%; margin-left:10%;">
            <div style="display: flex;width:100%;">
              <div style="width:50%;">
              <h4>First name</h4>
              <input type="text" name="fname" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your First name"id="FirstName"><br>
              <span class="error"><?php echo $fnameErr;?></span>
            </div>
            <div style="width:50%;">
                <h4>Last name</h4>
                <input type="text" name="lname" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your Last name"id="LastName"><br>
                <span class="error"><?php echo $lnameErr;?></span>
              </div>
            </div>
        </div>

        <br>
    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Email address</h4>
          <input  type="email" name="email" style="width:90%;height: 50px;border-radius: 5px;" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email address"id="EmailAddress"><br>
          <span class="error"><?php echo $emailErr;?></span>
        </div>
        <div style="width:50%;">
            <h4>Mobile</h4>
            <input type="number" name="mobile" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your mobile number" id="MobileNumber"><br>
            <span class="error"><?php echo $mobileErr;?></span>
          </div>
        </div>
    </div>   
    <h6><p style="margin-left:10%">We'll never share your email with anyone else.</p></h6>
    
    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Password</h4>
          <input type="password" name="password" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Password" id="Password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[!@#$%^&*+`~'=?\|\]\[\(\)\->/]).{6,12}"><br>
          <span class="error"><?php echo $passwordErr;?></span>
        </div>
        <div style="width:50%;">
            <h4>Confirm Password</h4>
            <input type="password" name="confirmation" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Re-type your password"id="RePassword">
          </div>
        </div>
    </div>   
    <h6><p style="margin-left:10%">Password should consist of at least 1 uppercase 1 lowercase and 1 special character with 6-12 characters in length.</p></h6>

    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Address</h4>
          <input type="text" name="address" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Address"id="Address"><br>
          <span class="error"><?php echo $addressErr;?></span>
        </div>
        <div style="width:50%;">
            <h4>Country</h4>
            <input type="text" name="country" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your city"id="City"><br>
            <span class="error"><?php echo $countryErr;?></span>
          </div>
        </div>
    </div>  
    <br> 
    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;" id="FFF">
            <h4>ABN</h4>
            <input type="number" name="abn" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your ABN number"id="ABN"><br>
            <span class="error"><?php echo $abnErr;?></span>
          </div>
          <div style="width:50%;">
            <h4>Upload Avatar</h4>
            <input type="file" name="avatar" style="width:90%;height: 50px;border-radius: 5px;" id="avatar"><br>
            <span class="error"><?php echo $avatarErr;?></span>
          </div>
        </div>
        <a href="login.php">Already have account?</a> 
    </div>   
        <div style="width:76%; margin-top: 50px;margin-left: 10%;">
         <button style="width:20%; height:50px;border-radius: 10px;">Cancel</button>
         <input type="submit" style="width:20%; height:50px; background-color: rgb(60, 133, 228);float: right;border-radius: 10px;" value="submit"></input>
        </div>
      </form>
      </div>
    </body>

    <script type="application/javascript">
	
      function gradeChange (){
            var FFF = document.getElementById("FFF")
			var AAA = document.getElementById("AAA")
            if (AAA.value == '1'){
                        FFF.style.display = 'block'
	
					}
			if(AAA.value == '2' ){
                        FFF.style.display = 'none'
					}
		} 
	</script>
	<style>
		.sss{
			font-size: 24px;
		}
	</style>
</html>
