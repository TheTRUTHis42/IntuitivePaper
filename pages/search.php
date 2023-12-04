<?php
session_start(); // Start the session at the beginning of the script
?>

<html lang = "en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-85R8SV1X10"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-85R8SV1X10');
    </script>
    <title>ArXiv Database Search</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
<?php
//database connection
$mysql = new mysqli(
    "webdev.iyaserver.com",
    "louisxie_user1",
    "sampleimport",
    "louisxie_IPImportTest"
);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//connection error test
if($mysql->connect_errno){
    echo "Database Connection Error:". $mysql->connect_errno;
    exit();
}
//create querey that pulls from database
$sql = "SELECT sub_categories FROM sub_categories ";

//save the query into a variable
$results = $mysql->query($sql);
echo $_SESSION['seclv'];
?>

<div class="w-full">
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

    <img src="assets/background.svg" class="absolute left-0 top-0 w-full" style="z-index: -1; min-height: 300px;" />
    <div class="mt-52 py-8 px-4 h-full" style="background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 20%, rgba(255,255,255,1) 100%);">
        <div class="max-w-2xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold" style="color: #2D1F63;">Intuitive Paper</h1>
                <p class="mt-3 text-lg font-medium text-gray-500">Find the best research on ArXiv</p>
                <form id="searchForm" action="results.php" method="GET" class="space-y-3">
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
            <div class="mt-16 text-center">
                <h5 class="text-lg text-gray-500">Try searching</h5>
                <div class="mt-3 flex flex-row items-center justify-center flex-wrap">
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">vision-enabled LLMs</span>
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">consumer nuclear energy</span>
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">generative diffusion models</span>
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">LK-99</span>
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">cryptography algorithms</span>
                    <span class="search-term-button mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">text-to-speech recognition</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    document.getElementById('searchFilter').addEventListener('change', function() {
        var searchInput = document.getElementById('searchInput');
        var selectedFilter = this.value;
        searchInput.name = selectedFilter;
    });

    var buttons = document.querySelectorAll('.search-term-button');

    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var searchTerm = this.getAttribute('data-search-term');
            var searchInput = document.getElementById('searchInput');
            var searchForm = document.getElementById('searchForm');
            searchInput.value = searchTerm;
            // searchForm.submit();
        });
    });
</script>
</html>
