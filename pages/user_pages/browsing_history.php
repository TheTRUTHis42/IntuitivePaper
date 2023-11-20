<?php
session_start();

// Database connection
$mysql = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysql->connect_errno) {
    die("Connection failed: " . $mysql->connect_error);
}

if (isset($_SESSION['userLoggedIn'], $_SESSION['userId'], $_GET['id']) && $_SESSION['userLoggedIn'] != true){
    echo "Error. Please login first to access this page.";
    exit();
}else{
    $userId = $_SESSION['userId']; // Assuming user ID is stored in session
    $browsesql = "SELECT * From paper_x_user WHERE user_id = ".$userId;

    $result = $mysql->query($browsesql);

    if(!$result) {
        echo "Your SQL: " . $browsesql . "<br><br>";
        echo "SQL Error: " . mysqli_error($conn);
        exit();
    }

    echo "<a href = 'user_accounts.php'>My Accounts  <a/>";
    echo "<a href = 'browsing_history.php'>Browsing History <a/>";
    echo "<a href = 'recommendation.php'>Recommendation Management <a/>";

    echo"<h1>Browsing History</h1>";
    while ($currentrow = $result -> fetch_assoc()) {
        $papersql = "SELECT title, sub_category, prim_category, browse_time FROM paper_category_view, paper_x_user 
                    WHERE paper_category_view.paper_id = paper_x_user.paper_id AND
                          paper_x_user.user_id = ".$userId;
        $result2 = $mysql->query($papersql);
        if(!$result) {
            echo "Your SQL: " . $browsesql . "<br><br>";
            echo "SQL Error: " . mysqli_error($conn);
            exit();
        }
        while ($currenthistory = $result -> fetch_assoc()) {
            echo "<strong>".$currenthistory["title"]."</strong><br>";
            echo $currenthistory["sub_category"]."<br>";
            echo $currenthistory["prim_category"]."<br>";
            echo $currenthistory["browse_time"]."<br>";


        }


    }

}
