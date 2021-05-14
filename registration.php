<?php
  $servername = "localhost";
  $username = "root";
  $password = "pangxiaotao";

  $connection = new mysqli($servername, $username, $password);
  if(!$connection){
    die("can't connect".mysqli_error());
}

  $username = $_POST["username"];
  $password = $_POST["password"];
  $encodePW = md5($password);
  $email = $_POST["email"];
  $mobile =$_POST["mobile"];
  $userid = random_int(1000000,9999999);

  $_SESSION['userid'] = "$userid"

  $query = "INSERT INTO users (userid, username, useremail, usermobile) VALUES ('userid', 'username', 'encodePW', 'email', 'mobile');";
  $result = $connection-> query($query);

  if ($result){
    $creatAccounts = "INSERT INTO 'account (`username`, `userid`, `password`, `email`, `mobile`) VALUES ('$username', '$userid', '$password', '$email', '$mobile');";
    $result = mysqli_multi_query($connection,$creatAccounts);

    if($result){
      header ("location: ..\\afterreg.php")
    }
   
    


  }else{
    echo ("registration failed");
  }
  $mysqli_free_result($result);
  $connection -> close();
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
    </head>
    <!-- Body -->

    <style>
      .mainDiv {
        background-image: url("img/regi\ 2.jpeg");
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
          <form>
              <br>
              <br>
               <h2><p align="center">CREATE ACCOUNT</p></h2>
               <br>
               <h4><p style="margin-left:10%">Are you registering as a client or host?</p></h4>
          <select style="width: 76%;height:50Px;margin-left: 10%;border-radius: 5px;" id="AAA" onchange="gradeChange()">
				    <option value="1" style="font-size: 16px;font-weight: 200;background-color: dimgrey;height: 50px;">Host</option>
				    <option value="2" style="font-size: 16px;font-weight: 200;background-color: dimgrey;height: 50px;">Client</option>
			   </select>  
            <br>
            <br>
        <div style="width:80%; margin-left:10%;">
            <div style="display: flex;width:100%;">
              <div style="width:50%;">
              <h4>First name</h4>
              <input type="text" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your First name"id="FirstName"required>
            </div>
            <div style="width:50%;">
                <h4>Last name</h4>
                <input type="text" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your Last name"id="LastName"required>
              </div>
            </div>
        </div>

        <br>
    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Email address</h4>
          <input  type="email" style="width:90%;height: 50px;border-radius: 5px;" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email address"id="EmailAddress"required>
        </div>
        <div style="width:50%;">
            <h4>Mobile</h4>
            <input type="number" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your mobile number"id="MobileNumber"required>
          </div>
        </div>
    </div>   
    <h6><p style="margin-left:10%">We'll never share your email with anyone else.</p></h6>
    
    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Password</h4>
          <input type="password"style="width:90%;height: 50px;border-radius: 5px;" placeholder="Password"id="Password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[!@#$%^&*+`~'=?\|\]\[\(\)\->/]).{6,12}"required>
        </div>
        <div style="width:50%;">
            <h4>Confirm Password</h4>
            <input type="password" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Re-type your password"id="RePassword"required>
          </div>
        </div>
    </div>   
    <h6><p style="margin-left:10%">Password should consist of number and letters.</p></h6>

    <div style="width:80%; margin-left:10%;">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>Address</h4>
          <input type="text" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Address"id="Address"required>
        </div>
        <div style="width:50%;">
            <h4>Country</h4>
            <input type="text" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your city"id="City"required>
          </div>
        </div>
    </div>  
    <br> 
    <div style="width:80%; margin-left:10%;"id="FFF">
        <div style="display: flex;width:100%;">
          <div style="width:50%;">
          <h4>ABN</h4>
          <input type="number" style="width:90%;height: 50px;border-radius: 5px;" placeholder="Enter your ABN number"id="ABN"required>
          </div>
        </div>   
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
        // function submit1 (){
        // 	  var AAA = document.getElementById("AAA")
	      //     var ABN = document.getElementById("ABN");
	      //     if(AAA.value == '1'){
	      //     	if(ABN.value == null || ABN.value == ""){
	      //     		alert("please enter your ABN")
	      //     		return
	      //     	}
	      //     	checkoutAll();
	      //     }else{
	      //     	checkoutAll();
	      //     }
        // }
        // function checkoutAll (){
        //    var firstName = document.getElementById("FirstName");
        //    var LastName = document.getElementById("LastName");
        //    var Emailaddress = document.getElementById("EmailAddress");
        //    var Mobilenumber = document.getElementById("MobileNumber");
        //    var Password = document.getElementById("Password");
        //    var RePassword = document.getElementById("RePassword");
        //    var Address = document.getElementById("Address");
        //    var City = document.getElementById("City");
        //  if ( firstName.value == null || firstName.value == ""){
        //    alert("please enter your First Name")
        //    return
        //  }else if ( LastName.value == null || LastName.value == ""){
        //      alert("please enter your Last Name")
        //    return
        //  }else if ( Emailaddress.value == null || Emailaddress.value == ""){
        //      alert("please enter your Email address")
        //    return
        //  }else if ( Mobilenumber.value == null || Mobilenumber.value == ""){
        //      alert("please enter your Mobile number")
        //    return
        //  }else if ( Password.value == null || Password.value == ""){
        //      alert("please enter your Password")
        //    return
        //  }else if ( RePassword.value == null || RePassword.value == ""){
        //      alert("please enter your Password again")
        //    return
        //  }else if (Password.value != RePassword.value){
        //   alert("Password difference")
        //   return
        //  }
        //  else if ( Address.value == null || Address.value == ""){
        //      alert("please enter your Address")
        //    return
        //  }else if ( City.value == null || City.value == ""){
        //      alert("please enter your City")
        //    return
        //  }else{
        //    alert("successfull submit") 
        //  }
        // }
	</script>
	<style>
		.sss{
			font-size: 24px;
		}
	</style>
</html>
