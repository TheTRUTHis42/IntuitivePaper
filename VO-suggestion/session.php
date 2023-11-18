<?php
//
//ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//
//// Some content here
//ob_end_clean(); // Discard the output buffer
session_start();
// if the user is already logged in then send to [welcome] page
if(isset($_SESSION['userid']) && $_SESSION['userid'] == true){
    header("location: welcome.php");
}
else{
    header("location: ../pages/login.php");
}
?>