<?php
session_start();
session_destroy();

echo "<H1>Logged out successfully!</H1> <br>";

echo"<style>
body{
    background-color: lightgoldenrodyellow;

.topnav {
    overflow: hidden;
    background-color: aquamarine;
    }

    .topnav a {
    float: left;
    color: blueviolet;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
    }

    .topnav a:hover {
    background-color: lightblue;
    color: black;
    }

    

</style>
<body>
<div class= topnav>
  <a href=Homepage.html>Homepage</a>
  <a href=login.php>Login</a>
  <a href=admin_login.php>Admin Login</a>
  <a href=register.php>Register</a>
</div>
</body>
";


 
?>

