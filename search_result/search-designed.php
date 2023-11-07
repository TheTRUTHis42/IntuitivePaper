<html lang = "en">
<head>
    <title>ARX-IF Database Search</title>
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
$sql = "SELECT categories FROM xie_import_6000 ";

//save the query into a variable
$results = $mysql->query($sql);
?>

<div className="w-full">
    <img src="assets/background.svg" class="absolute left-0 top-0 w-full" style="z-index: -1; min-height: 300px;" />
    <div class="mt-52 py-8 px-4 h-full" style="background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 20%, rgba(255,255,255,1) 100%);">
        <div class="max-w-2xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold" style="color: #2D1F63;">Intuitive Paper</h1>
                <p class="mt-3 text-lg font-medium text-gray-500">Find the best research on ArXiv</p>
                <form action="results-designed.php" method="GET" class="mt-8 flex flex-row items-center space-x-4">
                    <input name="search_title" class="w-full shadow-sm py-4 px-7 rounded-full border border-gray-200 focus:outline-none text-lg bg-white" type="text" placeholder="Search here" />
                    <input type="hidden" name="author" value="" placeholder="Author">
                    <input type="hidden" name="doi" value="" placeholder="Search by doi">
                    <input type="hidden" name="categories" value="ALL" placeholder="Search by doi">
                    <button style="background-color: #2D1F63;" class="rounded-full p-4 shadow-sm border-none focus:outline-none">
                        <img src="assets/search.svg" class="w-8" />
                    </button>
                </form>
            </div>
            <div class="mt-16 text-center">
                <h5 class="text-lg text-gray-500">Try searching</h5>
                <div class="mt-3 flex flex-row items-center justify-center flex-wrap">
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">vision-enabled LLMs</span>
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">consumer nuclear energy</span>
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">generative diffusion models</span>
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">LK-99</span>
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">cryptography algorithms</span>
                    <span class="mr-2 mb-2 border border-gray-200 rounded-full py-1 px-4 text-gray-500 text-md cursor-pointer hover:bg-gray-100">text-to-speech recognition</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

