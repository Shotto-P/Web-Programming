<?php
   
   $servername = "localhost";
   $username = "root";
   $password = "pangxiaotao";
 
   $connection = new mysqli($servername, $username, $password);
   if (!$connection) {
       die("can't connect".mysqli_error());
   };
    $userid = $_POST["cID"];
    $password = $_POST["cPW"];
    $encodePW = md5($password);
   session_start();
   
   //insert data to table
   $query = "SELECT * FROM `users` WHERE (userid = '$userid') AND (userpw = '$encodePW');";
   $result = $connection->query($query);
   
   $count =mysqli_num_rows($result);
       // If result matched $username and $mypassword, table row must be 1 row
        
       if ($count == 1) {
           $_SESSION['login_user'] = $username;
         
           header("location: account.php");
       } else {
           $error = "Your Login Name or Password is invalid";
       }
   }
   $mysqli_free_result($result);
   $connection -> close();
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body>
	
      <div>
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form id= "loginForm" action = "" method = "post">
               <h3 style="text-align: center">Login</h3>
               <p>
                  <label>userid :</label><br>
                    <input type = "text" name = "cId" class = "box"/><br><br>
                  
                  <label>Password  :</label><br>
                    <input type = "password" name = "cPW" class = "box" /><br><br>
                  
                  <input type = "submit" value = " Submit "/><br>
               </p>
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>