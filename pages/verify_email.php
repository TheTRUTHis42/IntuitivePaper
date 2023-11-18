<?php
// Database credentials
$host = 'webdev.iyaserver.com'; 
$username = 'louisxie_user1'; 
$password = 'sampleimport'; 
$database = 'louisxie_IPImportTest'; 

// Create a database connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the token is provided in the URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare a statement to select the user based on the provided token
    if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE email_verification_token = ?")) {
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // User found with the token
            $updateStmt = $mysqli->prepare("UPDATE users SET is_email_verified = 1 WHERE email_verification_token = ?");
            $updateStmt->bind_param("s", $token);
            $updateStmt->execute();

            echo "Email verification successful. Your account is now active.";
        } else {
            // No user found with the provided token
            echo "Invalid or expired verification link.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the database query.";
    }
} else {
    echo "No verification token provided.";
}

$mysqli->close();
?>
