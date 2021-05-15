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

<div>
    <p style="width: 30%; height: 40%; top: 30% left: 40%; position: relative;">
        <label style="font: size 30px;">
            Your User ID is:
        </label>
        <label style="color: blue; font-size: 36px">
            <?php
            session_start();
            echo $_SESSION[userid];
            ?>
        </label>
    </p>
</div>
</body>
</html>