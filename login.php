<?php
  require("connection.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Login Page</title>
</head>
<body>

    <div class="login-form">
        <h2>ADMIN LOGIN</h2>
        <form method="POST">
            <div class="input-field">
                <i class="bi bi-person-circle"></i>
                <input type="text" placeholder="Username" name ="admin_name">
            </div>
            <div class="input-field">
                <i class="bi bi-shield-lock"></i>
                <input type="password" placeholder="Password" name="admin_password">
            </div>
            
            <button type="submit" name="login">Login</button>
    
            <div class="extra">
                <a href="#">Forgot Password ?</a>
            </div>
    
        </form>
    </div>

    <?php

    if(isset($_POST['login'])){
        $query = "SELECT * FROM `adminlogin` WHERE `admin_name` = '$_POST[admin_name]' AND `admin_password` = '$_POST[admin_password]'";
        $result=mysqli_query($conn,$query);
        if(mysqli_num_rows($result)==1){
            session_start();
            $_SESSION['AdminLoginId']=$_POST['AdminName'];
            header("Location: Admin_Panel.php");
        }else{
            // Echo JavaScript to display an alert with the message
            echo "<script>alert('Username or Password Incorrect');</script>";
        }
    }

    ?>
    
</body>
</html>