<?php
session_start();

// if the user is already logged in then send to [welcome] page
if(isset($_SESSION['userid']) && $_SESSION['userid'] != true){
    header("location: login.php");
}
echo "Welcome to the administration page";
?>

