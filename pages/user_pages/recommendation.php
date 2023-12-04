<?php
session_start();
?>
<html>
    <body>
    <a href="../search.php">
        <img src="../assets/logo.svg"/><br><br>
    </a>
    </body>
</html>
<?php

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
        echo "<form action = 'delete_category.php'> <input type='hidden' name = 'id' value = ". $currentrow['user_x_category_id'] . ">";
        echo "<input type='submit'value = 'Delete'>"."<br>";
    }

    echo "</form><form action = 'update_category.php'><h3>Add Category</h3>";
    $sql = "SELECT * FROM sub_categories ";
    //save the query into a variable
    $results = $mysql->query($sql);
    echo "<select name ='category''> ";
    while ($currentrow = $results->fetch_assoc()){
        echo "<option name = 'category' value ='" . $currentrow['sub_categories_id'] . "'>" .
            $currentrow['sub_categories_name'] . "</option>";
    }
    echo "</select><input type='submit' ></form>";
}

?>

