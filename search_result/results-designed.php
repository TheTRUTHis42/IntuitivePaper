<html lang = "en">
<head>
    <title>ARX-IF Database Search Results</title>
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
$searchTitle = $_REQUEST['search_title'];
$searchAuthor = $_REQUEST['author'];
$searchDOI = $_REQUEST['doi'];
$searchCategory = $_REQUEST['categories'];

//filters
if($searchTitle != ''){
    $sql.= "AND title LIKE '%". $searchTitle. "%'";
}if($searchAuthor!= ''){
    $sql.= "AND authors LIKE '". $searchAuthor. "%'";
}if($searchDOI!= ''){
    $sql.= "AND doi = ". $searchDOI;
}if($searchCategory != "ALL"){
    $sql.= "AND categories = '". $searchCategory. "'";
}
//store results in a appropriate variables variable
$results = $mysql->query($sql);

print_r($results->fetch_assoc());
?>

<div className="w-full" style="background-image: url('assets/background.svg'); background-repeat: no-repeat; background-size: cover;">
    <div class="mt-32 py-8 bg-white">
        <div class="max-w-2xl mx-auto">
            <div class="text-center">
                <div class="flex flex-row items-center space-x-4">
                    <input class="w-full shadow-sm py-4 px-7 rounded-full border border-gray-200 focus:outline-none text-lg bg-white" type="text" placeholder="Search here" />
                    <button style="background-color: #2D1F63;" class="rounded-full p-4 shadow-sm border-none focus:outline-none">
                        <img src="assets/search.svg" class="w-8" />
                    </button>
                </div>
            </div>
            <div class="mt-10 w-full border border-gray-200 rounded-2xl shadow-sm p-5">
                <h5 class="text-xl font-semibold">"<?php echo $searchTitle; ?>"</h5>
                <p class="mt-2 text-md text-gray-500">We found <?php echo $results->num_rows; ?> matches.</p>
                <div class="mt-5 space-y-6">
                  <?php
                    while($curentrow = mysqli_fetch_assoc($results)) {
                  ?>
                    <div class="flex flex-row items-start space-x-4">
                      <span class="text-xl font-medium">[1]</span>
                        <div class="">
                          <div class="flex flex-row items-center space-x-3">
                          <h5 class="text-lg font-medium">Title</h5>
                          <div class="flex flex-row items-center justify-center flex-wrap">
                            <span class="mr-2 mb-2 border border-gray-200 rounded-full py-0.5 px-3 text-gray-500 text-xs cursor-pointer hover:bg-gray-100">vision-enabled LLMs</span>
                          </div>
                        </div>
                        <p class="mt-0 text-md text-gray-500"><?php echo $curentrow['doi']; ?></p>
                        <a class="block text-blue-400 underline cursor-pointer hover:text-blue-500 focus:outline-none" href="" target="_blank" rel="noreferrer">Click here for the full PDF</a>
                      </div>
                    </div>
                  <?php
                    }
                  ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

