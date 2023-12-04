<?php
session_start();
?>
<html>
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-85R8SV1X10"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-85R8SV1X10');
        </script>
    </head>
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
    $usersql = "SELECT * From users WHERE user_id = ".$userId;

    $result = $mysql->query($usersql);

    if(!$result) {
        echo "Your SQL: " . $usersql . "<br><br>";
        echo "SQL Error: " . mysqli_error($conn);
        exit();
    }

    $user = mysqli_fetch_assoc($result);
    echo "<a href = 'user_accounts.php'>My Accounts  <a/>";
    echo "<a href = 'browsing_history.php'>Browsing History <a/>";
    echo "<a href = 'recommendation.php'>Recommendation Management <a/>";

    echo"<form action = 'account_editing.php'>
        <input type='hidden' name = 'userid' value = '".$user['user_id']."'>";
    echo"<h1>Account Information</h1>";
    echo "<h2>Username</h2>";
    echo $user['username'];
    echo "<br><input type = 'text' name = 'username' value = ".$user['username'].">";
    echo "<h2>Email</h2>";
    echo $user['email'];
    echo "<br><input type = 'text' name = 'email' value = ".$user['email'].">";
    echo "<h2>My Bio</h2>";
    echo $user['user_bio'];
    echo "<br><input type = 'text' name = 'bio' value = ".$user['user_bio'].">";

    echo "<h2>My Interests</h2>";
    $interestsql = "SELECT sub_category From userview WHERE user_id = ".$userId;
    echo $interestsql;
    $result2 = $mysql->query($usersql);
    if (!$result2){
        "Set my interest here";
    }else{
//        $interest = mysqli_fetch_assoc($result2);
        while ($currentrow = $result2 -> fetch_assoc()) {
            echo $currentrow['sub_category'];
        }

    }
    echo "<input type = 'submit' value = 'Change Info'>";



}
