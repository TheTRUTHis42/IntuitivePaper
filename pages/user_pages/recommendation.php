<?php
session_start();
?>
<html>
    <head>
        <style>
            #container {
                padding: 30px;
                background-color: olive;
                text-align: left;
                color:white;
            }

            .box {
                float:left;
                text-align:right;
                height: 20px;
                width: 200px;
                margin: 5px;
            }
            .bar {
                background-color: red; min-width: 30px;
            }
        </style>
    </head>
    <body>
        <a href="../search.php">
            <img src="../assets/logo.svg"/><br><br>
        </a>


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

    echo "<h1>Recommendation Management</h1>";
    while ($currentrow = $result->fetch_assoc()) {
        echo $currentrow['sub_categories_name'];
        echo "<form action = 'delete_category.php'> <input type='hidden' name = 'id' value = " . $currentrow['user_x_category_id'] . ">";
        echo "<input type='submit'value = 'Delete'>" . "<br>";
    }

    echo "</form><form action = 'update_category.php'><h3>Add Category</h3>";
    $sql = "SELECT * FROM sub_categories ";
    //save the query into a variable
    $results = $mysql->query($sql);
    echo "<select name ='category''> ";
    while ($currentrow = $results->fetch_assoc()) {
        echo "<option name = 'category' value ='" . $currentrow['sub_categories_id'] . "'>" .
            $currentrow['sub_categories_name'] . "</option>";
    }
    echo "</select><input type='submit' ></form>";

    $sql1 = "SELECT COUNT(*) AS totalsubcategory FROM sub_categories";

    $results1 = $mysql -> query($sql1);

    $sql2 = "SELECT COUNT(*) AS totalcategory, prim_category_id
         FROM sub_categories
         GROUP BY prim_category_id";
    $results2 = $mysql -> query($sql2);

    $currentrow1 = $results1->fetch_assoc();
    echo "<br><br>Totals visualized...<strong>".$currentrow1["totalsubcategory"]."</strong><br>";
    $results2 = $mysql -> query($sql2);
    while($currentrow = $results2 ->fetch_assoc()){
        echo "<div class='box'>".$currentrow["prim_category_id"].":</div>";

        echo "    <div class='box bar' style='width:".(floatval($currentrow["totalcategory"])/2)."px'>&nbsp;</div>";
        echo $currentrow["totalcategory"]."<br style='clear:both'>";
    }
}


?>

