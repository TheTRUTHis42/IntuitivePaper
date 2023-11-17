<?php
// Database connection
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);

// Check for database connection error
if ($mysql->connect_errno) {
    echo "<p class='text-red-500'>Database Connection Error: " . $mysql->connect_errno . "</p>";
    exit();
}

// Get paper ID from URL
$id = $_GET['id'];

// Create SQL query to fetch paper details
$sql = "SELECT * FROM xie_import_6000 WHERE id = '" . $mysql->real_escape_string($id) . "'";

// Execute query
$result = $mysql->query($sql);

// Check for errors
if (!$result) {
    echo "<p class='text-red-500'><hr>SQL Error: " . $mysql->error . "<br></p>";
    exit();
}

// Fetch the paper details
$paper = mysqli_fetch_assoc($result);

if ($paper) {
    // Display paper details in a format suitable for the floating window
    echo "<h1 class='text-3xl font-bold mb-2'>" . htmlspecialchars($paper['title']) . "</h1>";
    echo "<p class='text-md mb-1'><strong>DOI:</strong> " . htmlspecialchars($paper['doi']) . "</p>";
    echo "<p class='text-md mb-1'><strong>Authors:</strong> " . htmlspecialchars($paper['authors']) . "</p>";
    echo "<p class='text-md mb-1'><strong>Categories:</strong> " . htmlspecialchars($paper['categories']) . "</p>";
    echo "<p class='text-md text-gray-600'>" . nl2br(htmlspecialchars($paper['abstract'])) . "</p>";
} else {
    echo "<p class='text-md text-gray-500'>Paper not found.</p>";
}
?>
