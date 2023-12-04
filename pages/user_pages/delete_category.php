<?php

session_start();

if(empty($_REQUEST['id'])) {
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
    $sql = "DELETE FROM user_x_category
        WHERE 
            user_x_category_id = ". $_REQUEST["id"];


    $results = $mysql->query($sql);

    if(!$results) {
        echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
        echo "SQL Error: " . $mysql->error . "<hr>";
        exit();
    } else{
        echo "<a href= 'recommendation.php'> Success! Back to last page </a>";
    }
}


