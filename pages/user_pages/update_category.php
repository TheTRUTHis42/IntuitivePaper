<?php

session_start();

if(empty($_REQUEST['category'])) {
    echo "Please go through Recommendations page.";
    echo "<a href= 'recommendation.php'>";
//    header('Location: search_drilldown.php');
    exit();
}


// Database connection
$mysql = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysql->connect_errno) {
    die("Connection failed: " . $mysql->connect_error);
}

if (isset($_SESSION['userLoggedIn'], $_SESSION['userId'], $_GET['id']) && $_SESSION['userLoggedIn'] != true){
    echo "Error. Please login first to access this page.";
    exit();
}else {

    $userId = $_SESSION['userId'];
    $sql = "INSERT INTO user_x_category (user_id, sub_category_id)
        VALUES (". $userId . ", ".$_REQUEST["category"]. ")";


    $results = $mysql->query($sql);

    if(!$results) {
        echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
        echo "SQL Error: " . $mysql->error . "<hr>";
        exit();
    } else{
        echo "<a href= 'recommendation.php'> Success! Back to last page </a>";
    }
}


