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
    <style>
    /* General Styles */
        body {
            font-family: 'Avenir', sans-serif;
            background: #FBFDFF;
            color: #212D40;
        }
        
        .flex {
            display: flex;
        }
        
        /* Sidebar Styles */
        .w-64 {
            width: 256px; /* Adjusted from 64px for better visibility */
        }
        
        .min-h-screen {
            min-height: 100vh;
        }
        
        .bg-gray-300 {
            background-color: #d1d5db; /* Light grey */
        }
        
        ul {
            list-style-type: none;
            padding: 0;
        }
        
        li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #212D40;
            font-weight: 800;
        }
        
        li a.bg-gray-700 {
            background-color: #374151; /* Dark grey */
        }
        
        /* Main Content Styles */
        .flex-1 {
            flex: 1;
        }
        
        .p-10 {
            padding: 10px;
        }
        
        /* Specific Element Styles */
        h2 {
            font-size: 51.8489px;
            line-height: 45px;
            color: #212D40;
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table, th, td {
            border: 0.942707px solid #000000;
        }
        
        th, td {
            text-align: left;
            padding: 8px;
        }
        
        th {
            background-color: #FBFDFF;
            color: #212D40;
            font-weight: 400;
        }
        
        td {
            background-color: #FFFFFF;
        }
        
        button {
            background-color: #92A8CA;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 9.42707px;
        }
        
        /* Responsive Adjustments */
        @media screen and (max-width: 768px) {
            .flex {
                flex-direction: column;
            }
        
            .w-64 {
                width: 100%;
            }
        
            .min-h-screen {
                min-height: auto;
            }
        }
        
        .sidebar li a {
            color: white;
        }
        
        .sidebar li a:hover {
            color: #f0f0f0; /* Slightly lighter color on hover*/
        }

    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 min-h-screen bg-gray-900 text-black">
        <ul class="sidebar">
            <li><a href="admin_page.php?tab=accounts" class="<?php echo $activeTab == 'accounts' ? 'bg-gray-700' : ''; ?>">Accounts</a></li>
            <li><a href="admin_page.php?tab=utilities" class="<?php echo $activeTab == 'utilities' ? 'bg-gray-700' : ''; ?>">Utilities</a></li>
            <li><a href="admin_page.php?tab=charts" class="<?php echo $activeTab == 'charts' ? 'bg-gray-700' : ''; ?>">Charts</a></li>
        </ul>
    </div>
    <!-- Main Content -->
    <div class="flex-1 p-10">
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

</body>
</html>
