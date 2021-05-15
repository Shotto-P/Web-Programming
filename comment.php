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

    if(isset($_POST["selectedAccomid"])){
        $_SESSION["selectedAccomid"]=$_POST["selectedAccomid"];
    }

    $selectedAccomid = $_SESSION["selectedAccomid"];
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
    <div>
        <br>
                    <h3>Comments On <?php echo $selectedAccom["Title"];?></h3>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Author</th>
                                <th>Content</th>
                                <th>Rate</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlForComment = "SELECT * FROM Comments INNER JOIN Users ON 
                                Comments.Author_id = Users.User_id WHERE Comments.Accom_id = ".$selectedAccomid;
                                $CommentFound = $connection->query($sqlForComment);
                                if($CommentFound->num_rows>0){
                                    while($row=$CommentFound->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $row["Fname"]; ?></td>
                                            <!--for the long content, we hide that with "..." using text-overflow in order to not breaking the design-->
                                            <td><div class="comments"><?php echo $row["Content"];?></div></td>
                                            <td><?php echo $row["Rate"];?></td>
                                            <td>
                                                <?php if($row["Author_id"]==$loginUserid){?>
                                                    <div style="display:flex;">
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>;" method="POST">
                                                            <a type="button" class="btn btn-outline-info" onclick="popupEdit()">Edit</a>
                                                            <div class="popupPayment" id="popupEdit">
                                                                <h4>Comment Detail: </h4>
                                                                <label class="payLabel">Rate</label>
                                                                <input type="radio" id="1" name="rate" value="1"><label for="1">1</label>
                                                                <input type="radio" id="2" name="rate" value="2"><label for="2">2</label>
                                                                <input type="radio" id="3" name="rate" value="3"><label for="3">3</label>
                                                                <input type="radio" id="4" name="rate" value="4"><label for="4">4</label>
                                                                <input type="radio" id="5" name="rate" value="5"><label for="5">5</label>
                                                                <br>
                                                                <label class="payLabel">Comments</label>
                                                                <input type="text" name="comment" class="commentInput" value="<?php echo $row["Content"];?>"><br>
                                                                <div style="display: flex;">
                                                                    <input type="submit" name="edit" class="btn btn-outline-info PayBtn" value="Edit">
                                                                    <a class="btn btn-outline-info closePayBtn" onclick="popupEditClose()">Close</a>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="form_number" value="<?php $row["Comment_id"];?>">
                                                            <input type="submit" name="remove" class="btn btn-outline-info" value="Remove">
                                                        </form>
                                                        <?php
                                                            if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                                if(isset($_POST["form_number"])){
                                                                    if($_POST["form_number"]==$row["Comment_id"]){
                                                                        if(isset($_POST["remove"])){
                                                                            $sqlForRemove = "DELETE FROM Comments WHERE Comment_id = ".$row["Comment_id"];
                                                                            if($connection->query($sqlForRemove)===TRUE){
                                                                            
                                                                            }else{
                                                                                echo $connection->error;
                                                                            }
                                                                        }
                                                                        if(isset($_POST["edit"])){
                                                                            $sqlForEdit="UPDATE Comments SET Content = ".$_POST["comment"]." Rate = ".$_POST["rate"]." WHERE Comment_id= ".$row["Comment_id"];
                                                                            if($connection->query($sqlForRemove)===TRUE){
                                                                            
                                                                            }else{
                                                                                echo $connection->error;
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                <?php }else{ ?>
                                                        <form action="commentDetail.php" method="POST">
                                                            <input type="hidden" name="selectedCommentid" value="<?php echo $row["Comment_id"]?>">
                                                            <input type="submit" name="view" class="btn btn-outline-info" value="View">
                                                        </form>
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{ ?>
                                    <h4 style="text-weight: bold; text-align: center;">There is no review on this accommodation.</h4>
                                <?php }
                                $connection->close();
                            ?>
                        </tbody>
                    </table>
    </div>
 
</body>
</html>














