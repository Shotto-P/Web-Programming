<?php
    session_start();
    include_once 'databaseConn.php';
    $userid = $_SESSION["loginUserid"];
    $sql = "SELECT * FROM Users WHERE User_id = ".$userid;
    $result = $connection->query($sql);
    $user=$accom="";
    if($result->num_rows > 0){
       $user = $result->fetch_assoc();
       //echo $user["Fname"];
       //echo "successful!";
    }else{
        echo $connection->error;
    }

    if(isset($_POST["editAccomid"])){
        $_SESSION["editAccomid"]=$_POST["editAccomid"];
    }

    $accomid = $_SESSION["editAccomid"];
    $sqlForAccom="SELECT * FROM Accommodations WHERE Accom_id = ".$accomid;
    $accomFound = $connection->query($sqlForAccom);
    if($accomFound->num_rows > 0){
        $accom = $accomFound->fetch_assoc();
        //echo $accom["Title"];
        //echo "successful!";
    }else{
        echo $connection->error;
    }
    $error = false;
    $titleErr = $priceErr = $addressErr = $cityErr = $countryErr = $guestErr =  $roomErr = $carparkErr = $bathErr = $imageErr = "";
    $title = $price = $address = $city =$country = $room = $guest = $bath = $carpark = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["form_number"])){
            if($_POST["form_number"]==3){
                if(empty($_POST["title"])||$_POST["title"]==" "){
                    $titleErr = "* Title is required!";
                    $error = true;
                }else{
                    $title = $_POST["title"];
                }
                if(empty($_POST["price"])){
                    $priceErr = "* Price is required!";
                    $error = true;
                }else{
                    $price = $_POST["price"];
                }
                if(empty($_POST["address"])||$_POST["address"]==" "){
                    $addressErr = "* Address is required!";
                    $error = true;
                }else{
                    $address = $_POST["address"];
                }
                if(empty($_POST["city"])||$_POST["city"]==" "){
                    $cityErr = "* City is required!";
                    $error = true;
                }else{
                    $city = $_POST["city"];
                }
                if(empty($_POST["country"])||$_POST["country"]==" "){
                    $countryErr = "* Country is required!";
                    $error = true;
                }else{
                    $country = $_POST["country"];
                }
                if(empty($_POST["GuestNo"])){
                    $guestErr = "* Number of Guest Allowed is required!";
                    $error = true;
                }else{
                    $guest = $_POST["GuestNo"];
                }
                if(empty($_POST["RoomNo"])){
                    $roomErr = "* Number of Room is required!";
                    $error = true;
                }else{
                    $room = $_POST["RoomNo"];
                }
                if(empty($_POST["BathroomNo"])){
                    $bathErr = "* Number of Bathroom is required!";
                    $error = true;
                }else{
                    $bath = $_POST["BathroomNo"];
                }
                if(empty($_POST["CarParkNo"])){
                    $carparkErr = "* Number of Carpark is required!";
                    $error = true;
                }else{
                    $carpark = $_POST["CarParkNo"];
                }
            
          
                if(isset($_POST["title"], $_POST["price"], $_POST["address"], $_POST["city"], $_POST["GuestNo"], $_POST["RoomNo"], $_POST["BathroomNo"], $_POST["CarParkNo"])&&$error==false){
                    $_SESSION["title"] = $title;
                    $_SESSION["price"] = $price;
                    $_SESSION["address"] = $address;
                    $_SESSION["city"] = $city;
                    $_SESSION["GuestNo"] = $guest;
                    $_SESSION["RoomNo"] = $room;
                    $_SESSION["BathroomNo"] = $bath;
                    $_SESSION["CarParkNo"] = $carpark;
                    if(!empty($_POST["smokeAllowed"])){
                        $_SESSION["smokeAllowed"] = 1;
                    }else{
                        $_SESSION["smokeAllowed"] = 0;
                    }
                    if(!empty($_POST["petAllowed"])){
                        $_SESSION["petAllowed"] = 1;
                    }else{
                        $_SESSION["petAllowed"] = 0;
                    }
                    if(!empty($_POST["InternetConnected"])){
                        $_SESSION["InternetConnected"] = 1;
                    }else{
                        $_SESSION["InternetConnected"] = 0;
                    }
                
                    $hostid = $_SESSION["loginUserid"];
                    $sqlForUpdate="UPDATE Accommodations SET Title='".$title."', Price=".$price.", Address='".$address."', 
                    City='".$city."', MaxGuestNum=".$guest.", NumOfRoom=".$room.", NumOfBath=".$bath.", NumOfCarPark=".
                    $carpark.", SmokeAllowed=".$_SESSION["smokeAllowed"].", PetAllowed=".$_SESSION["petAllowed"].", InternetConnected=".
                    $_SESSION["InternetConnected"]." WHERE Accom_id=".$accomid;
                    if($connection->query($sqlForUpdate)===TRUE){

                    }else{
                        echo $connection->error;
                    }
                    $connection->close();
                    header("Location: host.php");
                    exit();
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
                    <a class="dropdown-item" href="#">Edit</a>
                    <a class="dropdown-item" href="#">Log out</a>
                </div>
            </div>
        </div>

        <!--this is the form for creating accommodation for hosts-->
        <div class="createAccForm">
            <h2>Create an Accommodation</h2>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="form_number" value="3">
                <label class="formLabel">Property Title</label>
                <input type="text" id = "title" name="title" value="<?php echo $accom["Title"];?>">
                <span class="error"><?php echo $titleErr;?></span>
                <br>
                <label class="formLabel">Price</label>
                <input type="number" id = "price" name="price" value="<?php echo $accom["Price"];?>">
                <span class="error"><?php echo $priceErr;?></span>
                <br>
                <label class="formLabel">Address</label>
                <input type="text" id = "address" name="address" value="<?php echo $accom["Address"];?>">
                <span class="error"><?php echo $addressErr;?></span>
                <br>
                <label class="formLabel">City</label>
                <input type="text" id = "city" name="city" value="<?php echo $accom["City"];?>">
                <span class="error"><?php echo $cityErr;?></span>
                <br>
                <label class="formLabel">Country</label>
                <input type="text" id = "country" name="country" value="<?php echo $accom["Country"];?>">
                <span class="error"><?php echo $countryErr;?></span>
                <br>
                <label class="formLabel">Number of Guest Allowed</label>
                <input type="number" id = "GuestNo" name="GuestNo" min="1" value="<?php echo $accom["MaxGuestNum"];?>">
                <span class="error"><?php echo $guestErr;?></span>
                <br>
                <label class="formLabel">Number of Room</label>
                <input type="number" id = "RoomNo" name="RoomNo" min="1" value="<?php echo $accom["NumOfRoom"];?>">
                <span class="error"><?php echo $roomErr;?></span>
                <br>
                <label class="formLabel">Number of Bathroom</label>
                <input type="number" id = "BathroomNo" name="BathroomNo" min="0" value="<?php echo $accom["NumOfBath"];?>">
                <span class="error"><?php echo $bathErr;?></span>
                <br>
                <label class="formLabel">Number of carparking</label>
                <input type="number" id = "CarParkNo" name="CarParkNo" min="0" value="<?php echo $accom["NumOfCarPark"];?>">
                <span class="error"><?php echo $carparkErr;?></span>
                <br>
                <!-- <label class="formLabel">Upload images</label>
                <input type="file" id = "image" name="image"><br>
                <span class="error"><?php echo $imageErr;?></span>
                <br> -->
                <label class="formLabel">Smoked Allowed</label>
                <input type="checkbox" id="smokeAllowed" name="smokeAllowed" <?php echo ($accom["SmokeAllowed"]==1 ? 'checked' : '');?>><br>
                <label class="formLabel">Pet Allowed</label>
                <input type="checkbox" id="petAllowed" name="petAllowed" <?php echo ($accom["PetAllowed"]==1 ? 'checked' : '');?>><br>
                <label class="formLabel">Internet Provided</label>
                <input type="checkbox" id="InternetConnected" name="InternetConnected" <?php echo ($accom["InternetConnected"]==1 ? 'checked' : '');?>><br><br>
                <input type="submit" class="btn btn-outline-info createBtn" value="Submit Change">
            </form>
        </div>
    </body>
</html>