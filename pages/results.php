<html lang = "en">
<head>
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

//connection error test
if($mysql->connect_errno){
    echo "Database Connection Error:". $mysql->connect_errno;
    exit();
}

//sql statement
$sql = "SELECT * FROM xie_import_6000 WHERE 1 = 1 ";

//get search results
$all = $_REQUEST['all'];
$searchTitle = $_REQUEST['search_title'];
$searchAuthor = $_REQUEST['author'];
$searchDOI = $_REQUEST['doi'];
$searchCategories = $_REQUEST['categories'];
$filterType = $_REQUEST['filter_type'];

//filters
if($all != ''){
    $sql.= "AND title LIKE '%". $all. "%'" . "OR authors LIKE '%". $all. "%'" . "OR doi LIKE '%". $all. "%'" . "OR categories LIKE '%". $all. "%'" . "OR abstract LIKE '%". $all. "%'";
} else {
    if($searchTitle != ''){
        $sql.= "AND title LIKE '%". $searchTitle. "%'";
    }if($searchAuthor!= ''){
        $sql.= "AND authors LIKE '". $searchAuthor. "%'";
    }if($searchDOI!= ''){
        $sql.= "AND doi LIKE '%". $searchDOI. "%'";
    }if($searchCategories != "ALL"){
        $sql.= "AND categories LIKE '%". $searchCategories. "%'";
    }
}

print_r($sql);
//store results in a appropriate variables variable
$results = $mysql->query($sql);

//results error
if(!$results){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $sql. "</hr>";
}if(!$results){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $sql. "</hr>";
}if(!$results){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $sql. "</hr>";
}if(!$results){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $sql. "</hr>";
}
?>

<div className="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
    <div class="absolute top-0 w-full py-4 px-3" style="background: linear-gradient(to top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 100%);">
        <div class="max-w-6xl mx-auto flex flex-row items-center justify-between">
            <a href="search.php">
                <img src="assets/logo.svg" class="w-32" />
            </a>
            <div class="flex flex-row items-center space-x-2">
                <a href="login.php">
                    <button class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Login</button>
                </a>
                <a href="register.php">
                    <button style="border-color: #2D1F63; background-color: #2D1F63;" class="py-2 px-4 rounded-full border text-sm text-white">Register</button>
                </a>
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
                <h5 class="text-xl font-semibold">"<?php echo !empty($all) ? $all : (!empty($searchTitle) ? $searchTitle : (!empty($searchAuthor) ? $searchAuthor : (!empty($searchDOI) ? $searchDOI : $searchCategories))); ?>"</h5>
                <p class="mt-2 text-md text-gray-500">We found <?php echo $results->num_rows; ?> matches.</p>
                <p class="mt-1 text-sm italic text-gray-400">Filtering by <?php echo $filterType; ?> </p>
                <div class="mt-5 space-y-6">
                  <?php
                    $counter = 1;
                    while($row = mysqli_fetch_assoc($results)) {
                  ?>
                    <div class="flex flex-row items-start space-x-4">
                      <span class="text-xl font-medium">[<?php echo $counter; ?>]</span>
                      <div class="">
                        <div class="flex flex-row items-center space-x-3 flex-wrap">
                          <h5 class="text-lg font-medium">
                            <a href="details.php?id=<?php echo urlencode($row['id']); ?>" class="text-blue-900 hover:text-blue-500">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </h5>
                          <div class="flex flex-row items-center justify-center flex-wrap">
                            <?php
                            $categories = explode(', ', $row['categories']);
                            foreach ($categories as $category) {
                            ?>
                                <a href="results.php?filter_type=category&search_title=&author=&doi=&categories=<?php echo urlencode($category); ?>" class="mr-2 mb-2 border border-gray-200 rounded-full py-0.5 px-3 text-gray-500 text-xs cursor-pointer hover:bg-gray-100">
                                    <?php echo htmlspecialchars($category); ?>
                                </a>
                            <?php
                            }
                            ?>
                        </div>
                        </div>
                        <p class="mt-1 text-md text-gray-700"><?php echo $row['abstract']; ?></p>
                        <p class="mt-1 text-sm italic text-gray-400"><?php echo $row['authors']; ?> | <?php echo $row['doi']; ?></p>
                        <a class="mt-1 block text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none" href="https://arxiv.org/pdf/<?php echo $row['id']; ?>" target="_blank" rel="noreferrer">View PDF</a>
                      </div>
                    </div>
                  <?php
                    $counter++;
                    }
                  ?>
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

    window.onload = function() {
        var filterType = "<?php echo $filterType; ?>";
        var searchFilter = document.getElementById('searchFilter');
        searchFilter.value = filterType;
    };
</script>
</html>

