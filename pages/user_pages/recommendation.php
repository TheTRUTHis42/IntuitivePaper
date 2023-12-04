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
}else {
    $userId = $_SESSION['userId']; // Assuming user ID is stored in session
    $browsesql = "SELECT * From user_x_category, sub_categories WHERE sub_categories.sub_categories_id = user_x_category.sub_category_id AND user_id = " . $userId;

    $result = $mysql->query($browsesql);

    if (!$result) {
        echo "Your SQL: " . $browsesql . "<br><br>";
        echo "SQL Error: " . mysqli_error($conn);
        exit();
    }

    echo "<a href = 'user_accounts.php'>My Accounts  <a/>";
    echo "<a href = 'browsing_history.php'>Browsing History <a/>";
    echo "<a href = 'recommendation.php'>Recommendation Management <a/>";

    echo"<h1>Recommendation Management</h1>";
    while ($currentrow = $result -> fetch_assoc()) {
        echo $currentrow['sub_categories_name'];
        echo "<a href = 'delete_category.php'> Delete<a/>";
    }
    echo "<form action = 'add_category.php'><h3>Add Category</h3></form>";
    $sql = "SELECT * FROM sub_categories ";
    //save the query into a variable
    $results = $mysql->query($sql);
    echo "<select id='searchFilter''> ";
    while ($currentrow = $results->fetch_assoc()){
        echo "<option " .
            " value='" . $currentrow['sub_categories_id'] . "'>" .
            $currentrow['sub_categories_name'] . "</option>";
    }
}

?>

