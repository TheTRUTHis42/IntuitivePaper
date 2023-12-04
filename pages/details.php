<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-85R8SV1X10"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-85R8SV1X10');
    </script>
    <title>ArXiv Database Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .floating-window {
            position: fixed; /* Keeps the window in place relative to the viewport */
            right: 20px;
            top: 20px;
            width: 300px; /* Initial width */
            max-height: 80%; /* Maximum height */
            min-width: 150px; /* Minimum width */
            min-height: 150px; /* Minimum height */
            background-color: white;
            border: 1px solid #ddd;
            z-index: 1000;
            overflow: auto; 
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            resize: both; /* Enables resizing */
        }
    </style>

</head>
<body>
<?php
session_start();

// Database connection
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);

// Check for database connection error
if ($mysql->connect_errno) {
    echo "Database Connection Error: " . $mysql->connect_errno;
    exit();
}

// Check if a user is logged in and a paper ID is set
if (isset($_SESSION['userLoggedIn'], $_SESSION['userId'], $_GET['id']) && $_SESSION['userLoggedIn'] == true) {
    $userId = $_SESSION['userId']; // Assuming user ID is stored in session
    $paperId = $_GET['id']; // Paper ID as string

    // Insert record into paper_x_user
    $insertSql = "INSERT INTO paper_x_user (paper_id, user_id) VALUES (?, ?)";
    $stmt = $mysql->prepare($insertSql);
    if ($stmt === false) {
        die("Prepare failed: (" . $mysql->errno . ") " . $mysql->error);
    }

    // Bind parameters as string and integer respectively
    $stmt->bind_param("si", $paperId, $userId);

    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $stmt->close();
}

// Get paper ID from URL
$id = $_GET['id'];

// Create SQL query to fetch paper details
$sql = "SELECT * FROM paper_category_view WHERE paper_id = '" . $mysql->real_escape_string($id) . "'";

// Execute query
$result = $mysql->query($sql);

// Check for errors
if (!$result) {
    echo "<p class='text-red-500'><hr>SQL Error: " . $mysql->error . "<br></p>";
    exit();
}

// Fetch the paper details
$paper = mysqli_fetch_assoc($result);

if (!$paper) {
    echo "<p class='text-md text-gray-500'>Paper not found.</p>";
}

?>

<div class="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
    <div class="absolute top-0 w-full py-4 px-3" style="background: linear-gradient(to top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 100%);">
        <div class="max-w-6xl mx-auto flex flex-row items-center justify-between">
            <a href="search.php">
                <img src="assets/logo.svg" class="w-32" />
            </a>
            <div class="flex flex-row items-center space-x-2">
                <?php
                if (isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn'] == true) {
                    if (isset($_SESSION['seclv']) && $_SESSION['seclv'] == 5) {
                        // Admin user
                        echo '<a href="admin_page.php"><button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Admin</button></a>';
                    } else {
                        // Regular user
                        echo '<a href="my_account.php"><button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">My Account</button></a>';
                    }
                    // Logout button for logged-in users
                    echo '<a href="logout.php"><button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Logout</button></a>';
                } else {
                    // Not logged in
                    echo '<a href="login.php"><button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Login</button></a>';
                    echo '<a href="register.php"><button style="border-color: #2D1F63; background-color: #2D1F63;" class="py-2 px-4 rounded-full border text-sm text-white">Register</button></a>';
                }
                ?>
            </div>
        </div>
    </div>

        <div class="mt-32 py-8 bg-white">
        <div class="max-w-2xl mx-auto">
            <div class="max-w-2xl mx-auto mt-10 w-full border border-gray-200 rounded-2xl shadow-sm p-5">
                <?php
                    echo "<h1 class='text-3xl font-bold mb-2'>" . htmlspecialchars($paper['title']) . "</h1>";
                    echo "<p class='text-md mb-1'><strong>DOI:</strong> " . htmlspecialchars($paper['doi']) . "</p>";
                    echo "<p class='text-md mb-1'><strong>Authors:</strong> " . htmlspecialchars($paper['authors']) . "</p>";
                    echo "<p class='text-md mb-1'><strong>Categories:</strong> " . htmlspecialchars($paper['sub_category']) . "</p>";
                    echo "<p class='text-md text-gray-600'>" . nl2br(htmlspecialchars($paper['abstract'])) . "</p>";
                ?>
                <a class="mt-1 block text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none" href="https://arxiv.org/pdf/0<?php echo $paper['paper_id']; ?>" target="_blank" rel="noreferrer">View PDF</a>
                
            </div>
        </div>
    </div>
</div>

</body>
</html>