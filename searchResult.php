<!--in this page, it will show a list of all accommodations, and when hover the mouse on each 
accommodation, it will show more detail of the accommodation-->
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
    $city=$_SESSION["CitySearch"];
    $startdate=$_SESSION["CheckInDate"];
    $enddate=$_SESSION["CheckOutDate"];
    $guestNo=$_SESSION["GuestNoSearch"];
    $sql="SELECT * FROM Accommodations 
    WHERE City = '".$city."' AND MaxGuestNum >= ".$guestNo." AND 
    Accom_id NOT IN (SELECT Accom_id FROM Bookings WHERE ((StartDate BETWEEN '".$startdate."' AND '".$enddate.
    "') OR (EndDate BETWEEN '".$startdate."' AND '".$enddate."')) AND (Booking_status = 'Approved' OR Booking_status = 'Complete'))";
    if($result = $connection->query($sql)){
        //echo "search successfully";
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

        <div class="container">
            <div id="accommodations" class="container">
                <br>
                <div>
                    <h3 class="AccDBBar">Accommodations</h3>
                </div>
                <?php
                    if($result->num_rows>0){
                        while($row=$result->fetch_assoc()){?>
                            <div class="responsive">
                                <div class="gallery">
                                    <div class="imageContainer">
                                        <div class="overlay">
                                            <p><strong>Address:</strong></p>
                                            <p><?php echo $row["Address"];?>, <?php echo $row["City"];?>, <?php echo $row["Country"];?></p>
                                            <p><strong>Host: </strong><?php 
                                                $sqlForHost="SELECT * FROM Users WHERE User_id = ".$row["Host_id"];
                                                $resultHost = $connection->query($sqlForHost);
                                                if($resultHost->num_rows>0){
                                                    $host = $resultHost->fetch_assoc();
                                                    echo $host["Fname"];
                                                }
                                            ?> (<?php echo $host["Rate"];?>)</p>
                                            <p><?php
                                                if($row["SmokeAllowed"]==0){
                                                    echo "Smoke Not Allowed";
                                                }else{
                                                    echo "Smoke Allowed";
                                                }
                                            ?></p>
                                            <p><?php
                                                if($row["PetAllowed"]==0){
                                                    echo "Pet Not Allowed";
                                                }else{
                                                    echo "Pet Allowed";
                                                }
                                            ?></p>
                                            <p><?php
                                                if($row["InternetConnected"]==0){
                                                    echo "Internet Not Provided";
                                                }else{
                                                    echo "Internet Provided";
                                                }
                                            ?></p>
                                        </div>
                                        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['Image']);?>" alt="HouseImage" width="250" height="210" class="AccoImages">
                                    </div>
                                    <div class="desc">
                                        <h4><?php echo $row["Title"];?></h4>
                                        <i class="fas fa-bed" style="font-size: 24px; margin-left: 3px; color:#a6a6a6;"><?php echo $row["NumOfRoom"];?></i>
                                        <i class="fas fa-bath" style="font-size: 24px; margin-left: 3px; color: #a6a6a6;"><?php echo $row["NumOfBath"];?></i>
                                        <i class="fas fa-car" style="font-size: 24px; margin-left: 3px; color: #a6a6a6;"><?php echo $row["NumOfCarPark"];?></i>
                                        <i class="fas fa-star" style="font-size: 24px; float: right; color:#ff4d4d;"><a style="color:black;"><?php echo $row["Rate"];?></a></i>
                                        <br>
                                        <span><strong>$<?php echo $row["Price"];?> AUD</strong>/night</span>
                                        <br>
                                        <?php if(!isset($_SESSION["loginUserid"])){?>
                                            <a href="login.php" class="btn btn-outline-info bookBtn">Login to Book</a>
                                        <?php }else{ ?>
                                            <form method="POST" action="confirmPage.php">
                                                <input type="hidden" name="selectedAccomid" value="<?php echo $row["Accom_id"];?>">
                                                <input type="submit" name="book" class="btn btn-outline-info bookBtn" value="Book">
                                            </form>
                                        <?php } 
                                        ?>
                                        <form method="POST" action="comment.php">
                                            <input type="hidden" name="selectedAccomid" value="<?php echo $row["Accom_id"];?>">
                                            <input type="submit" name="comment" class="btn btn-outline-info bookBtn" value="Reviews">
                                        </form>
                
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }else{ ?>
                        <h4 style="text-weight: bold; text-align: center;">There is no result matched your search.</h4>
                    <?php }
                    $connection->close();
                ?>
            </div>

        </div>
    </body>
</html>