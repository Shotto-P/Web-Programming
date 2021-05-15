<?php
    session_start();
    include_once "databaseConn.php";

    $loginUserid=$loginUser=$selectedComment="";
    if(isset($_SESSION["loginUserid"])){
        $loginUserid = $_SESSION["loginUserid"];
        $sqlForUser = "SELECT * FROM Users WHERE User_id = ".$loginUserid;
        $resultFound = $connection->query($sqlForUser);
        if($resultFound->num_rows>0){
            $loginUser = $resultFound->fetch_assoc();
        } 
    }

    if(isset($_POST["selectedCommentid"])){
        $_SESSION["selectedCommentid"]=$_POST["selectedCommentid"];
    }

    $selectedCommentid = $_SESSION["selectedCommentid"];
    $sqlForComment = "SELECT * FROM Comments INNER JOIN Users ON Comments.Author_id = Users.User_id WHERE Comment_id = ".$selectedCommentid;
    $commentFound = $connection->query($sqlForComment);
    if($commentFound->num_rows>0){
        $selectedComment = $commentFound->fetch_assoc();
    }

    if($loginUserid=$selectedComment["Receiver_id"]){
        //update the status of this comment to read
        $sqlForUpdate="UPDATE Comments SET Status = 'Read' WHERE Comment_id = ".$_SESSION["selectedCommentid"];
        if($connection->query($sqlForUpdate)===TRUE){

        }else{
            echo $connection->error;
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
        <h3>Comment</h3>
        <p><?php echo $selectedComment["Content"];?></p>
        <span>Written by <?php echo $selectedComment["Fname"];?></span>
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














