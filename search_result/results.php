<html lang = "eng">
<head>
    <title>ARX-IF Results</title>
    <style>

    </style>
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
$sql = "SELECT * FROM xie_import_6000 ";


//get search results
$searchTitle = $_REQUEST['search_title'];
$searchAuthor = $_REQUEST['author'];
$searchDOI = $_REQUEST['doi'];
$searchCategory = $_REQUEST['categories'];

//filters
if($searchTitle){
    $sql.= "WHERE title LIKE '%". $searchTitle. "'%";
}if($searchAuthor){
    $sql.= "WHERE author LIKE '%". $searchAuthor. "'%";
}if($searchDOI){
    $sql.= "WHERE doi =". $searchDOI;
}if($searchCategory != "ALL"){
    $sql.= "WHERE categories =". $searchCategory;
}
//sotre results in a variable called results
$results = $mysql->query($sql);

//results error
if(!$results){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $sql. "</hr>";
}

// Display search terms and record count
echo "<div id='title'>";
echo "<h1>Search Results</h1>";
echo "You searched for class: <strong>$searchTitle</strong>";
echo "Number of records found: <strong>" . $results->num_rows . "</strong><br><br>";
echo "</div>";

?>
</body>
<!--display results-->
    <div id="container">
        <?php
            while($currentrow = mysqli_fetch_assoc($results)){
                echo "<div class='fields'><strong>title:</strong>";
                echo $currentrow[$searchTitle]. "<br>";
                echo "<strong>DOI:</strong>". $currentrow['doi']. "<br>";
                echo "<strong>Author:</strong>".$currentrow['author']."<br>";
                echo "<strong>Category:</strong>".$currentrow['categories'];
                echo "</div><br>";
            }


        ?>
    </div>
</html>
