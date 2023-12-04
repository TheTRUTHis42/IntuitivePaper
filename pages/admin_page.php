<?php
session_start();


// Check if user is logged in and has admin privileges
if (!isset($_SESSION['userLoggedIn']) || $_SESSION['seclv'] != 5) {
    header("Location: login.php");
    exit();
}

// Database connection
$mysql = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");

// Check for database connection error
if ($mysql->connect_errno) {
    echo "Database Connection Error: " . $mysql->connect_errno;
    exit();
}

// Functionality for handling different tabs
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'accounts';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <!-- Link to external CSS file -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- For Charts -->
    
</head>
<body>
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
        <div class="flex max-w-6xl mx-auto mt-14">
            <!-- Sidebar -->
            <div class="w-64 min-h-screen">
                <ul class="space-y-2">
                    <li class="cursor-pointer"><a href="admin_page.php?tab=accounts" class="border-l-4 border-transparent py-2 px-5 text-lg <?php echo $activeTab == 'accounts' ? 'border-blue-300 font-bold text-blue-300' : ''; ?>">Accounts</a></li>
                    <li class="cursor-pointer"><a href="admin_page.php?tab=utilities" class="border-l-4 border-transparent py-2 px-5 text-lg <?php echo $activeTab == 'utilities' ? 'border-blue-300 font-bold text-blue-300' : ''; ?>">Utilities</a></li>
                    <li class="cursor-pointer"><a href="admin_page.php?tab=charts" class="border-l-4 border-transparent py-2 px-5 text-lg <?php echo $activeTab == 'charts' ? 'border-blue-300 font-bold text-blue-300' : ''; ?>">Charts</a></li>
                </ul>
            </div>
            <!-- Main Content -->
            <div class="flex-1 pl-8">
                <?php
                switch ($activeTab) {
                    case 'accounts':
                        include('admin_accounts.php'); // Separate PHP file for account management
                        break;
                    case 'utilities':
                        include('admin_utilities.php'); // Separate PHP file for utilities management
                        break;
                    case 'charts':
                        include('admin_charts.php'); // Separate PHP file for charts
                        break;
                }
                ?>
            </div>
        </div>
            </div>
            </div>
</body>
</html>
