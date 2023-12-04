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
        $papersql = "SELECT title, sub_category, prim_category, browse_time, paper_x_user.paper_id FROM paper_category_view, paper_x_user 
                    WHERE paper_category_view.paper_id = paper_x_user.paper_id AND
                          paper_x_user.user_id = ".$userId;
        $result2 = $mysql->query($papersql);

        if(!$result2) {
            echo "Your SQL: " . $browsesql . "<br><br>";
            echo "SQL Error: " . mysqli_error($conn);
            exit();
        }
        while ($currenthistory = $result2 -> fetch_assoc()) {
            echo "<strong><h3><a href = 'https://arxiv.org/pdf/".$currenthistory["paper_id"]."'target='_blank' rel='noreferrer'>".$currenthistory['title']."</a></h3></strong>";

            echo "<strong>Category: ".$currenthistory["prim_category"]."</strong>";
            echo " (".$currenthistory["sub_category"].")<br>";
            echo $currenthistory["browse_time"]."<br><hr>";


        }


    }

}
