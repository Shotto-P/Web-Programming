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

    $error=false;
    $fnameErr=$lnameErr=$emailErr=$mobileErr=$passwordErr=$addressErr=$countryErr=$imageErr="";
    $fname=$lname=$email=$mobile=$userpassword=$address=$country="";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["form_number"])){
            if($_POST["form_number"]==1){
            if(empty($_FILES["avatar"]["name"])){
                $error = true;
                $imageErr = "image upload required.";
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
            if(isset($_FILES["avatar"]["name"])&&$error==false){
                $stmt = $connection->prepare("UPDATE Users SET Avatar = ? WHERE User_id=".$userid);
                $stmt->bind_param("s", $imgContent);
                if($stmt->execute()){
                    //echo "Successful!";
                }else{
                    echo $stmt->error;
                }
                $stmt->close();
            }
            }else{
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

            if(isset($_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["mobile"], $_POST["password"], $_POST["confirmation"], $_POST["address"], $_POST["country"])&&$error==false){
                $_SESSION["fname"]=$fname;
                $_SESSION["lname"]=$lname;
                $_SESSION["email"]=$email;
                $_SESSION["mobile"]=$mobile;
                $_SESSION["password"]=$userpassword;
                $_SESSION["address"]=$address;
                $_SESSION["country"]=$country;
            
                $sqlForUpdate = "UPDATE Users SET Fname = '".$fname."', Lname = '".$lname."', Email = '".
                $email."', Mobile = '".$mobile."', Password = '".$userpassword."', Address = '".$address."', Country = '".
                $country."' WHERE User_id = ".$userid;
                if($connection->query($sqlForUpdate)===TRUE){
                    //echo "Record updated successfully";
                }else{
                    echo "Error updating record: ".$connection->error;
                }
            }
            }
        }
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
                    <a class="dropdown-item" href="home.php">Home</a>
                    <a class="dropdown-item" href="logout.php">Log out</a>
                </div>
            </div>
        </div>

        <div class="container">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#profile">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profileEdit">Edit Your Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#bookings">Manage Your Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#comments">Manage Your Comments</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="profile" class="container tab-pane active">
                    <br>
                    <h3>Profile</h3>
                    <div>
                        <div class="profileDiv">
                            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($user['Avatar']);?>" alt="avatar" class="avatar" width="350" height="300"><br>
                            <br>
                            <a class="btn btn-outline-info changAvatarBtn" id="changeAvatar" onclick="popup()">Change Avatar?</a>
                            <div class="popup" id = "popup">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="form_number" value="1">
                                    <label class="popupLabel">Upload images</label>
                                    <input type="file" id = "image" name="avatar" class="popupInput" required><br>
                                    <span class="error"><?php echo $imageErr; ?></span><br>
                                    <a class="btn btn-outline-info cancelBtn" id="popupCancel" onclick="closePopup()">Cancel</a>
                                    <input type="submit" class="btn btn-outline-info confirmBtn" value="Upload">
                                </form>
                            </div>
                            <br><br>
                            <h3><strong>Hi, I'm <?php echo $user["Fname"]." ".$user["Lname"]; ?></strong></h3>
                            <a>My Mobile Number is +61 <?php echo $user["Mobile"];?></a>
                        </div>
                        <div class="profileDiv IntroDiv">
                            <h3>About</h3>
                            <i class="fas fa-envelope" style="font-size: 24px; margin-right: 5px;"></i>My Email address is <?php echo $user["Email"];?><br>
                            <i class="fas fa-home" style="font-size: 24px; margin-right: 5px;"></i>Lives in <?php echo $user["Address"].", ".$user["Country"];?>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div id="profileEdit" class="container tab-pane fade">
                    <br>
                    <div>
                        <h3 class="AccDBBar">Manage Your Profile</h3>
                        <button type="submit" form="editform" class="AccDBBar btn btn-outline-info btnNewUser">Submit Change</button>
                    </div>
                    <div>
                        <form id="editform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                            <input type="hidden" name="form_number" value="2">
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
                                    <input type="email" name="email" class="editForm" value="<?php echo $user["Email"];?>" readonly><br>
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
                        </form>
                    </div>
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
                            $sqlForBookings = "SELECT * FROM Bookings INNER JOIN Accommodations 
                            ON Bookings.Accom_id = Accommodations.Accom_id WHERE Tenant_id = ".$userid;
                            $resultFound = $connection->query($sqlForBookings);
                            if($resultFound->num_rows>0){
                                while($row=$resultFound->fetch_assoc()){ ?>
                                    <tr>
                                        <td>
                                            <div class="description"><?php echo $row["Booking_id"];?></div>
                                        </td>
                                        <td>
                                            <?php
                                                $sqlForAccom = "SELECT Title FROM Accommodations WHERE Accom_id = ".$row["Accom_id"];
                                                $searchResult = $connection->query($sqlForAccom);
                                                $Accom="";
                                                if($searchResult->num_rows>0){
                                                    $Accom = $searchResult->fetch_assoc();
                                                }
                                            ?>
                                            <div class="description"><?php echo $Accom["Title"];?></div>
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
                                            <div class="description" id="priceTable"><?php echo $row["TotalPrice"];?></div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php if($row["Booking_status"]=="Approved"){?>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                                        
                                                        <input type="hidden" name="form_number" value="<?php echo $row["Booking_id"];?>">
                                                        <button type="button" class="btn btn-outline-info" onclick="popupPayment()">Pay</button>
                                                        <input type="submit" name="cancel" class="btn btn-outline-info" value="Cancel">
                                                        <div class="popupPayment" id="popupPayment">
                                                            <!-- <?php echo $row["Price"];?> -->
                                                            <h4>Payment Detail: </h4>
                                                            <label class="payLabel">Card Number</label>
                                                            <input type="text" id="card" name="cardnumber" placeholder="e.g. xxxx xxxx xxxx xxxx" oninput="validCard()"required>
                                                            <img class="visa" id="visa" src="img/visa.jpg" alt="visa" width="80px" height="40px">
                                                            <br>
                                                            <span id="VISAerror" class="error"></span>
                                                            <br>
                                                            <label class="payLabel">Total Price</label>
                                                            <input type="number">
                                                            <br>
                                                            <label class="payLabel">Rate</label>
                                                            <input type="radio" id="1" name="rate" value="1"><label for="1">1</label>
                                                            <input type="radio" id="2" name="rate" value="2"><label for="2">2</label>
                                                            <input type="radio" id="3" name="rate" value="3"><label for="3">3</label>
                                                            <input type="radio" id="4" name="rate" value="4"><label for="4">4</label>
                                                            <input type="radio" id="5" name="rate" value="5"><label for="5">5</label>
                                                            <br>
                                                            <label class="payLabel">Comments</label>
                                                            <input type="text" name="comment" class="commentInput"><br>
                                                            <div style="display: flex;">
                                                                <input type="submit" name="pay" class="btn btn-outline-info PayBtn" value="Proceed">
                                                                <a class="btn btn-outline-info closePayBtn" onclick="popupPaymentClose()">Close</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php 
                                                        if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                            if(isset($_POST["form_number"])){
                                                                if($_POST["form_number"]==$row["Booking_id"]){
                                                                    if(isset($_POST["pay"])){
                                                                        $sqlForPay="UPDATE Bookings SET Booking_status = 'Complete' WHERE Booking_id = ".$row["Booking_id"];
                                                                        if($connection->query($sqlForPay)===TRUE){
                                                                            //echo "Pay successfully";
                                                                        }else{
                                                                            echo "Error pay: ". $connection->error;
                                                                        }

                                                                        $sqlForLeaveComment=$connection->prepare("INSERT INTO Comments (Content, Author_id, Rate, Accom_id, Receiver_id) VALUES (?, ?, ?, ?, ?)");
                                                                        $sqlForLeaveComment->bind_param("siiii", $_POST["comment"], $userid, $_POST["rate"], $row["Accom_id"], $row["Host_id"]);
                                                                        $sqlForLeaveComment->execute();
                                                                        $sqlForLeaveComment->close();

                                                                        //update the rate of each property after new rate added
                                                                        $total=0;
                                                                        $size = 0;
                                                                        $sqlForRate = "SELECT * FROM Comments WHERE Accom_id = ".$row["Accom_id"];
                                                                        $RateResult = $connection->query($sqlForRate);
                                                                        if($RateResult->num_rows>0){
                                                                            while($rate=$RateResult->fetch_assoc()){
                                                                                $total = $total + $rate["Rate"];
                                                                                $size = $size + 1;
                                                                            }
                                                                            $finalRate = round($total/$size);
                                                                            //echo $finalRate;
                                                                            $sqlTest = "UPDATE Accommodations SET Rate = ".$finalRate." WHERE Accom_id = ".$row["Accom_id"];
                                                                            //echo $sqlTest;
                                                                            $sqlForUpdateRate = "UPDATE Accommodations SET Rate = ".$finalRate." WHERE Accom_id = ".$row["Accom_id"];
                                                                            if($connection->query($sqlForUpdateRate)===TRUE){

                                                                            }else{
                                                                                echo "Error updating rate: ". $connection->error;
                                                                            }
                                                                        }
                                                                    }
                                                                    if(isset($_POST["cancel"])){
                                                                        $sqlForCancel="DELETE FROM Bookings WHERE Booking_id = ".$row["Booking_id"];
                                                                        if($connection->query($sqlForCancel)===TRUE){
                                                                            //echo "Cancel successfully";
                                                                        }else{
                                                                            echo "Error cancel: ". $connection->error;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                <?php }?>
                                                <?php if($row["Booking_status"]=="Rejected"){?>
                                                    <button type="button" class="btn btn-outline-info" onclick="popupReason()">View Reason</button>
                                                    <div class="popupReason" id="popupReason">
                                                        <h4>Reason why been rejected: </h4>
                                                        <p class="reasonContent"><?php echo $row["Reason"];?></p>
                                                        <a class="btn btn-outline-info closeBtn" onclick="popupReasonClose()">Close</a>
                                                    </div>
                                                <?php }?>
                                                <?php if($row["Booking_status"]=="Pending"){?>
                                                    <div style="display:flex;">
                                                    <span>Waiting for Approval</span>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                        <input type="hidden" name="form_number" value="<?php echo $row["Booking_id"];?>">
                                                        <input type="submit" name="cancel" class="btn btn-outline-info viewCancelReasonBtn" value="Cancel">
                                                    </form>
                                                    </div>
                                                    <?php
                                                        if(isset($_POST["cancel"])){
                                                            $sqlForCancel="DELETE FROM Bookings WHERE Booking_id = ".$row["Booking_id"];
                                                            if($connection->query($sqlForCancel)===TRUE){
                                                                //echo "Cancel successfully";
                                                            }else{
                                                                echo "Error cancel: ". $connection->error;
                                                            }
                                                        }
                                                    ?>
                                                <?php }?>
                                                <?php if($row["Booking_status"]=="Complete"){?>
                                                    <span>Booking Complete</span>
                                                <?php }?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            }else{ ?>
                                <h4 style="text-weight: bold; text-align: center;">You do not have any booking right now.</h4>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="comments" class="container tab-pane fade">
                <br>
                    <h3>Comments Dashboard</h3>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Content</th>
                                <th>Rate</th>
                                <th>Status</th>
                                <th>Edit</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqlForComment = "SELECT * FROM Comments WHERE Author_id = ".$userid." OR Receiver_id = ".$userid;
                                $CommentFound = $connection->query($sqlForComment);
                                if($CommentFound->num_rows>0){
                                    while($row=$CommentFound->fetch_assoc()){ ?>
                                        <tr>
                                            <!--for the long content, we hide that with "..." using text-overflow in order to not breaking the design-->
                                            <td><div class="comments"><?php echo $row["Content"];?></div></td>
                                            <td><?php echo $row["Rate"];?></td>
                                            <?php if($row["Author_id"] != $userid){?>
                                                <td><?php echo $row["Status"];?></td>
                                            <?php }else{ ?>
                                                <td>My Comment</td>
                                            <?php } ?>
                                            <td>
                                                <div style="display: flex;">
                                                    <?php if($row["Author_id"]==$userid){?>
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                                            <input type="hidden" name="form_number" value="<?php echo $row["Comment_id"];?>">
                                                            <a type="button" class="btn btn-outline-info" onclick="popupEdit()">Edit</a>
                                                            <div class="popupEdit" id="popupEdit">
                                                                <h4>Comment Detail: </h4>
                                                                <label class="payLabel">Rate</label>
                                                                <input type="radio" id="1" name="rate" value="1"><label for="1">1</label>
                                                                <input type="radio" id="2" name="rate" value="2"><label for="2">2</label>
                                                                <input type="radio" id="3" name="rate" value="3"><label for="3">3</label>
                                                                <input type="radio" id="4" name="rate" value="4"><label for="4">4</label>
                                                                <input type="radio" id="5" name="rate" value="5"><label for="5">5</label>
                                                                <br>
                                                                <label class="payLabel">Comments</label>
                                                                <input type="text" name="comment" class="commentInput" ><br>
                                                                <div style="display: flex;">
                                                                    <input type="submit" name="edit" class="btn btn-outline-info PayBtn" value="Confirm">
                                                                    <a class="btn btn-outline-info closePayBtn" onclick="popupEditClose()">Close</a>
                                                                </div>
                                                            </div>
                                                            
                                                            <input type="submit" name="remove" class="btn btn-outline-info" value="Remove">
                                                        </form>
                                                        <?php
                                                            if($_SERVER["REQUEST_METHOD"]=="POST"){
                                                                if(isset($_POST["form_number"])){
                                                                    if($_POST["form_number"]==$row["Comment_id"]){
                                                                        if(isset($_POST["remove"])){
                                                                            $sqlForRemove = "DELETE FROM Comments WHERE Comment_id = ".$row["Comment_id"];
                                                                            if($connection->query($sqlForRemove)===TRUE){
                                                                                //echo "Remove successful.";
                                                                            }else{
                                                                                echo $connection->error;
                                                                            }
                                                                        }
                                                                        if(isset($_POST["edit"])){
                                                                            
                                                                            $sqlForEdit="UPDATE Comments SET Content = '".$_POST["comment"]."', Rate = ".$_POST["rate"]." WHERE Comment_id= ".$row["Comment_id"];
                                                                            if($connection->query($sqlForEdit)===TRUE){
                                                                            
                                                                            }else{
                                                                                echo $connection->error;
                                                                            }

                                                                            //update the rate of each property after new rate added
                                                                            $total=0;
                                                                            $size = 0;
                                                                            $sqlForRate = "SELECT * FROM Comments WHERE Accom_id = ".$row["Accom_id"];
                                                                            $RateResult = $connection->query($sqlForRate);
                                                                            if($RateResult->num_rows>0){
                                                                                while($rate=$RateResult->fetch_assoc()){
                                                                                    $total = $total + $rate["Rate"];
                                                                                    $size = $size + 1;
                                                                                }
                                                                                $finalRate = round($total/$size);
                                                                                //echo $finalRate;
                                                                                $sqlTest = "UPDATE Accommodations SET Rate = ".$finalRate." WHERE Accom_id = ".$row["Accom_id"];
                                                                                //echo $sqlTest;
                                                                                $sqlForUpdateRate = "UPDATE Accommodations SET Rate = ".$finalRate." WHERE Accom_id = ".$row["Accom_id"];
                                                                                if($connection->query($sqlForUpdateRate)===TRUE){

                                                                                }else{
                                                                                    echo "Error updating rate: ". $connection->error;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                }
                                                            }
                                                        ?>
                                                    <?php }else{ ?>
                                                        <form action="commentDetail.php" method="POST">
                                                            <input type="hidden" name="selectedCommentid" value="<?php echo $row["Comment_id"];?>">
                                                            <input type="submit" name="view" value="Read" class="btn btn-outline-info">
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{ ?>
                                    <h4 style="text-weight: bold; text-align: center;">You do not have any comment right now.</h4>
                                <?php }
                                $connection->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>