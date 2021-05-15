<?php
    session_start();
    include_once 'databaseConn.php';
    $error = false;
    $titleErr = $priceErr = $addressErr = $cityErr = $countryErr = $guestErr =  $roomErr = $carparkErr = $bathErr = $imageErr = "";
    $title = $price = $address = $city =$country = $room = $guest = $bath = $carpark = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
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
        if(empty($_FILES["image"]["name"])){
            $imageErr = "* image is required!";
            $error = true;
        }else{
            //manage the upload image
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg');
            if(in_array($fileType, $allowTypes)){
                $image = $_FILES["image"]["tmp_name"];
                $imgContent = file_get_contents($image);
            }else{
                $imageErr = "image file type need to be one of them: jpg, jpeg, png";
                $error=true;
            }
        }
    }
  
    if(isset($_POST["title"], $_POST["price"], $_POST["address"], $_POST["city"], $_POST["GuestNo"], $_POST["RoomNo"], $_POST["BathroomNo"], $_POST["CarParkNo"], $_FILES["image"]["name"])&&$error==false){
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
        $stmt = $connection->prepare("INSERT INTO Accommodations (Title, Address, City, Country, Price, Host_id, NumOfRoom, NumOfBath, NumOfCarPark, MaxGuestNum, SmokeAllowed, PetAllowed, InternetConnected, Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiiiiiiiis", $title, $address, $city, $country, $price, $hostid, $room, $bath, $carpark, $guest, $_SESSION["smokeAllowed"], $_SESSION["petAllowed"], $_SESSION["InternetConnected"], $imgContent);
        if($stmt->execute()){
            //echo "Successful!";
        }else{
            echo $stmt->error;
        }
        $stmt->close();
        $connection->close();
        header("Location: host.php");
        exit();
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
    </head>
    <body>
        <!--this is the top bar of each page-->
        <div class="topBar">
            <div class="barItem">
                <h3 class="brand">UniBnB</h3>
            </div>
            <div class="barItem iconDiv">
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <label class="formLabel">Property Title</label>
                <input type="text" id = "title" name="title">
                <span class="error"><?php echo $titleErr;?></span>
                <br>
                <label class="formLabel">Price</label>
                <input type="number" id = "price" name="price">
                <span class="error"><?php echo $priceErr;?></span>
                <br>
                <label class="formLabel">Address</label>
                <input type="text" id = "address" name="address">
                <span class="error"><?php echo $addressErr;?></span>
                <br>
                <label class="formLabel">City</label>
                <input type="text" id = "city" name="city">
                <span class="error"><?php echo $cityErr;?></span>
                <br>
                <label class="formLabel">Country</label>
                <input type="text" id = "country" name="country">
                <span class="error"><?php echo $countryErr;?></span>
                <br>
                <label class="formLabel">Number of Guest Allowed</label>
                <input type="number" id = "GuestNo" name="GuestNo" min="1">
                <span class="error"><?php echo $guestErr;?></span>
                <br>
                <label class="formLabel">Number of Room</label>
                <input type="number" id = "RoomNo" name="RoomNo" min="1">
                <span class="error"><?php echo $roomErr;?></span>
                <br>
                <label class="formLabel">Number of Bathroom</label>
                <input type="number" id = "BathroomNo" name="BathroomNo" min="0">
                <span class="error"><?php echo $bathErr;?></span>
                <br>
                <label class="formLabel">Number of carparking</label>
                <input type="number" id = "CarParkNo" name="CarParkNo" min="0">
                <span class="error"><?php echo $carparkErr;?></span>
                <br>
                <label class="formLabel">Upload images</label>
                <input type="file" id = "image" name="image"><br>
                <span class="error"><?php echo $imageErr;?></span>
                <br>
                <label class="formLabel">Smoked Allowed</label>
                <input type="checkbox" id="smokeAllowed" name="smokeAllowed"><br>
                <label class="formLabel">Pet Allowed</label>
                <input type="checkbox" id="petAllowed" name="petAllowed"><br>
                <label class="formLabel">Internet Provided</label>
                <input type="checkbox" id="InternetConnected" name="InternetConnected"><br><br>
                <input type="submit" class="btn btn-outline-info createBtn" value="Create">
            </form>
        </div>
    </body>
</html>