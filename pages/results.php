<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start the session at the beginning of the script

// Define items per page
$itemsPerPage = 25;

// Get the current page number from the URL, defaulting to page 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page is at least 1

// Calculate the offset for the query
$offset = ($page - 1) * $itemsPerPage;

// Database connection
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);

// Connection error test
if ($mysql->connect_errno) {
    echo "Database Connection Error: " . $mysql->connect_errno;
    exit();
}

// Check if search parameters are set
$all = isset($_REQUEST['all']) ? $_REQUEST['all'] : '';
$searchTitle = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
$searchAuthor = isset($_REQUEST['author']) ? $_REQUEST['author'] : '';
$searchDOI = isset($_REQUEST['doi']) ? $_REQUEST['doi'] : '';
$searchCategories = isset($_REQUEST['categories']) ? $_REQUEST['categories'] : '';
$filterType = $_REQUEST['filter_type'];

// Build the SQL query with filters
$sql = "SELECT * FROM paper_category_view WHERE 1 = 1 ";
$totalSql = "SELECT COUNT(*) as total FROM paper_category_view WHERE 1 = 1 "; // For total count

// Add conditions based on filters
if ($filterType == 'search_title' && $searchTitle != '') {
    $sql .= " AND title LIKE '%" . $searchTitle . "%'";
    $totalSql .= " AND title LIKE '%" . $searchTitle . "%'";
} elseif ($filterType == 'author' && $searchAuthor != '') {
    $sql .= " AND authors LIKE '%" . $searchAuthor . "%'";
    $totalSql .= " AND authors LIKE '%" . $searchAuthor . "%'";
} elseif ($filterType == 'doi' && $searchDOI != '') {
    $sql .= " AND doi LIKE '%" . $searchDOI . "%'";
    $totalSql .= " AND doi LIKE '%" . $searchDOI . "%'";
} elseif ($filterType == 'categories' && $searchCategories != 'ALL') {
    $sql .= " AND sub_category LIKE '%" . $searchCategories . "%'";
    $totalSql .= " AND sub_category LIKE '%" . $searchCategories . "%'";
} elseif ($all != '') {
    // If 'all' field is used, apply it to all categories
    $sql .= " AND (title LIKE '%" . $all . "%' OR authors LIKE '%" . $all . "%' OR doi LIKE '%" . $all . "%' OR sub_category LIKE '%" . $all . "%' OR abstract LIKE '%" . $all . "%')";
    $totalSql .= " AND (title LIKE '%" . $all . "%' OR authors LIKE '%" . $all . "%' OR doi LIKE '%" . $all . "%' OR sub_category LIKE '%" . $all . "%' OR abstract LIKE '%" . $all . "%')";
}

// Amend the SQL statement to include a LIMIT clause for pagination
$sql .= " LIMIT $offset, $itemsPerPage";

// Execute query
$results = $mysql->query($sql);

// Results error handling
if (!$results) {
    echo "<hr>SQL Error: " . $mysql->error . "<br>";
    echo "Output SQL: " . $sql . "</hr>";
    exit();
}

// Calculate total number of pages
$totalQuery = $mysql->query($totalSql);
if (!$totalQuery) {
    echo "<hr>SQL Error in Total Count Query: " . $mysql->error . "<br>";
    exit();
}
$totalRow = $totalQuery->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $itemsPerPage);
?>

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
                        echo '<a href="user_pages/user_accounts.php"><button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">My Account</button></a>';
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
            <div class="text-center">
                <form action="results.php" method="GET" class="space-y-3">
                    <div class="mt-8 flex flex-row items-center space-x-4">
                        <input type="hidden" name="author" value="" placeholder="Author">
                        <input type="hidden" name="doi" value="" placeholder="Search by doi">
                        <input type="hidden" name="categories" value="ALL" placeholder="Search by doi">
                        <input id="searchInput" name="all" class="w-full shadow-sm py-4 px-7 rounded-full border border-gray-200 focus:outline-none text-lg bg-white" type="text" placeholder="Search paper topics" />
                        <button style="background-color: #2D1F63;" class="rounded-full p-4 shadow-sm border-none focus:outline-none">
                            <img src="assets/search.svg" class="w-8" />
                        </button>
                    </div>
                    <div class="max-w-xs mx-auto">
                        <label for="location" class="mr-1 text-sm font-medium leading-6 text-gray-500">Filter by</label>
                        <select id="searchFilter" name="filter_type" style="width: 125px" class="text-sm mt-1 mx-auto w-full rounded-full border-0 py-1 pl-2 pr-5 text-gray-900 ring-1 ring-inset ring-gray-200 sm:text-sm sm:leading-6">
                            <option value="ALL" selected>ALL</option>
                            <option value="search_title">Title</option>
                            <option value="author">Author</option>
                            <option value="doi">DOI</option>
                            <option value="categories">Category</option>
                        </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="max-w-2xl mx-auto mt-10 w-full border border-gray-200 rounded-2xl shadow-sm p-5">
                <h5 class="text-xl font-semibold">
                    "<?php
                    if (!empty($all)) {
                        echo $all;
                    } elseif (!empty($searchTitle)) {
                        echo $searchTitle;
                    } elseif (!empty($searchAuthor)) {
                        echo $searchAuthor;
                    } elseif (!empty($searchDOI)) {
                        echo $searchDOI;
                    } elseif (!empty($searchCategories)) {
                        echo $searchCategories;
                    }
                    ?>"
                </h5>
                <p class="mt-2 text-md text-gray-500">We found <?php echo $results->num_rows; ?> matches.</p>
                <div class="mt-5 space-y-6">
                    <?php
                    $counter = 1;
                    while ($row = mysqli_fetch_assoc($results)) {
                        echo '<div class="flex flex-row items-start space-x-4">';
                            echo '<span class="text-xl font-medium">[' . $counter . ']</span>';
                            echo '<div class="">';
                                echo '<div class="flex flex-row items-center space-x-3 flex-wrap">';
                                    echo '<h5 class="text-lg font-medium">';
                                        echo '<a href="details.php?id=' . urlencode($row['paper_id']) . '" class="text-blue-900 hover:text-blue-500">' . htmlspecialchars($row['title']) . '</a>';
                                    echo '</h5>';
                                    echo '<div class="flex flex-row items-center justify-center flex-wrap">';
                                        $categories = explode(', ', $row['sub_category']);
                                        foreach ($categories as $category) {
                                            echo '<a href="results.php?search_title=&author=&doi=&categories=' . urlencode($category) . '" class="mr-2 mb-2 border border-gray-200 rounded-full py-0.5 px-3 text-gray-500 text-xs cursor-pointer hover:bg-gray-100">' . htmlspecialchars($category) . '</a>';
                                        }?>
                                    </div>
                                    <button onclick="openInMargin('<?php echo $row['paper_id']; ?>')" class="text-blue-500 hover:text-blue-700">Open in Margin</button>
                                </div>
                                <p class="mt-0 text-md text-gray-500"><?php echo $row['doi']; ?></p>
                                <p class="mt-1 text-sm italic text-gray-400">By: <?php echo $row['authors']; ?></p>
                                <p class="mt-1 text-sm text-gray-600"><?php echo nl2br(htmlspecialchars($row['abstract'])); ?></p>
                                <a class="mt-1 block text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none" href="https://arxiv.org/pdf/0<?php echo $row['paper_id']; ?>" target="_blank" rel="noreferrer">View PDF</a>
                            </div>
                        </div>
                            <!-- Pagination Links -->
                        <?php
                        $counter++;
                    }
                    ?>
                </div>
                <nav>
                    <?php
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                    }
                    ?>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
function openInMargin(paperId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var floatWindow = document.createElement("div");
            floatWindow.classList.add("floating-window");
            floatWindow.innerHTML = '<button onclick="closeFloatingWindow(this)" style="cursor: pointer; position: absolute; top: 5px; right: 5px;">Close</button>';
            floatWindow.innerHTML += this.responseText;

            document.body.appendChild(floatWindow);
            makeDraggable(floatWindow);
        }
    };
    xhttp.open("GET", "details.php?id=" + paperId, true);
    xhttp.send();
}

// Function to make the window draggable
function makeDraggable(elem) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    elem.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // Get the mouse cursor position at startup
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // Call a function whenever the cursor moves
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // Calculate the new cursor position
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // Set the element's new position
        elem.style.top = (elem.offsetTop - pos2) + "px";
        elem.style.left = (elem.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // Stop moving when mouse button is released
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

// Function to close the floating window
function closeFloatingWindow(button) {
    var floatWindow = button.parentNode;
    if (floatWindow) {
        floatWindow.remove();
    }
}
</script>

</body>
</html>
