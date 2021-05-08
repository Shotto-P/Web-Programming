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
            <form action="#" method="POST">
                <label class="formLabel">Property Title</label>
                <input type="text" name="title" required><br>
                <label class="formLabel">Price</label>
                <input type="number" name="price" required><br>
                <label class="formLabel">Number of Room</label>
                <input type="number" name="RoomNo" min="1" required><br>
                <label class="formLabel">Number of Bathroom</label>
                <input type="number" name="BathroomNo" min="0" required><br>
                <label class="formLabel">Number of carparking</label>
                <input type="number" name="CarParkNo" min="0" required><br>
                <label class="formLabel">Upload images</label>
                <input type="file" name="image"><br><br>
                <input type="submit" class="btn btn-outline-info createBtn" value="Create">
            </form>
        </div>
    </body>
</html>