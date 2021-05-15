<?php
    session_start();
    
    $userid = $_SESSION["loginUserid"];

    include_once 'databaseConn.php';

    $sql = "SELECT * FROM Users WHERE User_id = ".$userid;
    $result = $connection->query($sql);
    if($result->num_rows > 0){
       $user = $result->fetch_assoc();
       //echo $user["Fname"];
       //echo "successful!";
    }else{
        echo $connection->error;
    }
?>
<!DOCTYPE html>
<html>
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
    <body>
        <!--this is the top bar of each page-->
        <div class="topBar">
            <div class="barItem">
                <h3 class="brand">UniBnB</h3>
            </div>
            <div class="barItem iconDiv">
                <span style="font-weight: bold;"><?php if(isset($_SESSION["loginUserid"])){echo $user["Fname"];}?></span>
                <i class="fas fa-user-alt dropdown-toggle" data-toggle="dropdown" style="font-size: 24px;"></i>
                <div class="dropdown-menu">
                    
                    <a class="dropdown-item" href="logout.php">Log out</a>
                </div>
            </div>
        </div>

        <div class="container">
            <!--list for switching different tables-->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#accommodations">Accommodations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#users">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#reviews">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#bookings">Bookings</a>
                </li>
            </ul>
            <!--it is the table of all accommodation that admin can check-->
            <div class="tab-content">
                <div id="accommodations" class="container tab-pane active">
                    <br>
                    <h3>Accommodations Dashboard</h3>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Accommodation</th>
                                <th>Host</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlForAccom="SELECT * FROM Accommodations INNER JOIN Users ON Accommodations.Host_id=Users.User_id";
                                $AccomResult=$connection->query($sqlForAccom);
                                if($AccomResult->num_rows>0){
                                    while($row=$AccomResult->fetch_assoc()){ ?>
                                        <tr>
                                            <td>
                                                <div class="image">
                                                    <a href="#">
                                                    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['Image']);?>" alt="Accom Image" width="220" height="200">
                                                    </a>
                                                    <div class="description"><?php echo $row["Title"];?></div>
                                                </div>
                                            </td>
                                            <td><div class="hostName"><a href="#" class="text-muted"><?php echo $row["Fname"];?></a></div></td>
                                            <td>
                                                <div class="buttons">
                                                    <form action="editAccom.php" method="POST">
                                                        <input type="hidden" name="editAccomid" value="<?php echo $row["Accom_id"];?>">
                                                        <input type="submit" class="btn btn-outline-info editBtn" value="Edit">
                                                    </form>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                        <input type="hidden" name="form_number" value="<?php echo $row["Accom_id"];?>">
                                                        <input type="submit" name="remove" class="btn btn-outline-info removeBtn" value="Remove">
                                                    </form>
                                                    <?php
                                                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                            if(isset($_POST["form_number"])){
                                                                if($_POST["form_number"]==$row["Accom_id"]){
                                                                    $sqlForRemove="DELETE FROM Accommodations WHERE Accom_id = ".$row["Accom_id"];
                                                                        if($connection->query($sqlForRemove)===TRUE){
                                                                            //echo "Record deleted successfully";
                                                                        }else{
                                                                            echo "Error deleting record: ".$connection->error;
                                                                        }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!--this is the table of all users that the admin can check-->
                <div id="users" class="container tab-pane fade">
                    <br>
                    <div>
                        <h3 class="UserDBBar">Users Dashboard</h3>
                        <a href="registration.php" class="UserDBBar btn btn-outline-info btnNewUser">New User</a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlForUser="SELECT * FROM Users";
                                $UserResult=$connection->query($sqlForUser);
                                if($UserResult->num_rows>0){
                                    while($user=$UserResult->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $user["Fname"];?></td>
                                            <td>
                                                <?php
                                                    if($user["ABN"]==0){
                                                        echo "Client";
                                                    }else{
                                                        echo "Host";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <form action="editUser.php" method="POST">
                                                        <input type="hidden" name="editUserid" value="<?php echo $user["User_id"];?>">
                                                        <input type="submit" class="btn btn-outline-info editBtn" value="Edit">
                                                    </form>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                        <input type="hidden" name="form_number" value="<?php echo $user["User_id"];?>">
                                                        <input type="submit" name="remove" class="btn btn-outline-info removeBtn" value="Remove">
                                                    </form>
                                                    <?php
                                                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                            if(isset($_POST["form_number"])){
                                                                if($_POST["form_number"]==$user["User_id"]){
                                                                    $sqlForRemove="DELETE FROM Users WHERE User_id = ".$user["User_id"];
                                                                        if($connection->query($sqlForRemove)===TRUE){
                                                                            //echo "Record deleted successfully";
                                                                        }else{
                                                                            echo "Error deleting record: ".$connection->error;
                                                                        }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!--and this is the table of reviews-->
                <div id="reviews" class="container tab-pane fade">
                    <br>
                    <h3>Reviews</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Review</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlForComment="SELECT * FROM Comments INNER JOIN Users ON Comments.Author_id = Users.User_id";
                                $CommentResult=$connection->query($sqlForComment);
                                if($CommentResult->num_rows>0){
                                    while($comment=$CommentResult->fetch_assoc()){ ?>
                                        <tr>
                                            <!--for the long content, we hide that with "..." using text-overflow in order to not breaking the design-->
                                            <td><div class="comments"><?php echo $comment["Content"];?></div></td>
                                            <td><?php echo $comment["Fname"];?></td>
                                            <td><?php echo $comment["Status"];?></td>
                                            <td>
                                                <div>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                        <input type="hidden" name="form_number" value="<?php echo $comment["Comment_id"];?>">
                                                        <input type="submit" name="remove" class="btn btn-outline-info removeBtn" value="Remove">
                                                    </form>
                                                    <?php
                                                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                            if(isset($_POST["form_number"])){
                                                                if($_POST["form_number"]==$comment["Comment_id"]){
                                                                    $sqlForRemove="DELETE FROM Comments WHERE Comment_id = ".$comment["Comment_id"];
                                                                        if($connection->query($sqlForRemove)===TRUE){
                                                                            //echo "Record deleted successfully";
                                                                        }else{
                                                                            echo "Error deleting record: ".$connection->error;
                                                                        }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{ ?>
                                    <h4 style="text-weight: bold; text-align: center;">There is no comment for now.</h4>
                                <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="bookings" class="container tab-pane fade">
                <br>
                    <h3>Bookings Dashboard</h3>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Booking No.</th>
                                <th>Booking Hotel</th>
                                <th>Booking Status</th>
                                <th>Check in Date</th>
                                <th>Check out Date</th>
                                <th>Total Price</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sqlForBookings = "SELECT * FROM Bookings INNER JOIN Accommodations ON 
                            Bookings.Accom_id = Accommodations.Accom_id";
                            $resultFound = $connection->query($sqlForBookings);
                            if($resultFound->num_rows>0){
                                while($row=$resultFound->fetch_assoc()){ ?>
                                    <tr>
                                        <td>
                                            <div class="description"><?php echo $row["Booking_id"];?></div>
                                        </td>
                                        <td>
                                            <div class="description"><?php echo $row["Title"];?></div>
                                        </td>
                                        <td>
                                            <div class="description"><?php echo $row["Booking_status"];?></div>
                                        </td>
                                        <td>
                                            <div class="description"><?php echo $row["StartDate"];?></div>
                                        </td>
                                        <td>
                                            <div class="description"><?php echo $row["EndDate"];?></div>
                                        </td>
                                        <td>
                                            <div class="description"><?php echo $row["TotalPrice"];?></div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php
                                                    if($row["Booking_status"]=="Rejected"){?>
                                                        <button type="button" class="btn btn-outline-info" onclick="popupReason()">View Reason</button>
                                                        <div class="popupReason" id="popupReason">
                                                            <h4>Reason why been rejected: </h4>
                                                            <p><?php echo $row["Reason"];?></p>
                                                            <a class="btn btn-outline-info closeBtn" onclick="popupReasonClose()">Close</a>
                                                        </div>
                                                    <?php } elseif($row["Booking_status"]=="Complete"){?>
                                                        <span>Booking Complete</span>
                                                    <?php } else { ?>
                                                
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                            <input type="hidden" name="form_number" value="<?php echo $row["Booking_id"];?>">
                                                            <input type="submit" name="remove" class="btn btn-outline-info removeBtn" value="Remove">
                                                        </form>
                                                        <?php
                                                            if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                                if(isset($_POST["form_number"])){
                                                                    if($_POST["form_number"]==$row["Booking_id"]){
                                                                        $sqlForRemove="DELETE FROM Bookings WHERE Booking_id = ".$row["Booking_id"];
                                                                        if($connection->query($sqlForRemove)===TRUE){
                                                                            //echo "Record deleted successfully";
                                                                        }else{
                                                                            echo "Error deleting record: ".$connection->error;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                    } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            }else { ?>
                                <h4 style="text-weight: bold; text-align: center;">There is no booking for now.</h4>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>