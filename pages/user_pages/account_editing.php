<?php
session_start();

// Database connection
$mysql = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysql->connect_errno) {
    die("Connection failed: " . $mysql->connect_error);
}

if(empty($_REQUEST['userid'])) {
    echo "Please go through search page.";
    echo "<a href= 'user_accounts.php'>";
    exit();
} else {
    $sql = "UPDATE users
        SET 
            username = '". $_REQUEST["username"]."',
            email = '". $_REQUEST["email"]."',
            user_bio = '". $_REQUEST["bio"]."'
        WHERE 
            user_id = ". $_REQUEST['userid'];

    echo $sql;

    $result = $mysql->query($sql);

    if (!$result) {
        echo "Your SQL: " . $sql . "<br><br>";
        echo "SQL Error: " . $mysql->error . "<hr>";
        exit();
    }
}

echo "<em><hr><br><br>Your updated info is <strong>";
echo "<div><strong>" . $_REQUEST["username"] .
    "<br></strong>" .
    "<div> Email:" . $_REQUEST["email"] . "</div>" .
    "<div> Bio:" . $_REQUEST["bio"] . "</div>";

