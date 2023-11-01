<html lang = "eng">
<head>
    <title>ARX-IF Results</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
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

// Display search terms and record count
echo "<div id='title'>";
echo "<h1>Search Results</h1>";
if($searchTitle !=''){
    echo "You searched in Title for: <strong>$searchTitle</strong><br>";
    echo "Number of records found: <strong>" . $results->num_rows . "</strong><br><br>";

}if($searchAuthor !=''){
    echo "You searched in Author for: <strong>$searchAuthor</strong><br>";
    echo "Number of records found: <strong>" . $results->num_rows . "</strong><br><br>";

}if($searchDOI !=''){
    echo "You searched in DOI for: <strong>$searchDOI</strong><br>";
    echo "Number of records found: <strong>" . $results->num_rows . "</strong><br><br>";

}

echo "</div>";

?>
</body>
<!--display results-->
    <div id="container">
        <?php
            while($curentrow = mysqli_fetch_assoc($results)) {
                echo "<div class='fields'><strong>title:</strong> ";
                echo $curentrow['title'] . "<br>";
                echo "<strong>Author: </strong>" . $curentrow['authors'] . "<br>";
                echo "<strong>Category: </strong>" . $curentrow['categories'] . "<br>";
                echo "<strong>DOI: </strong>" . $curentrow['doi'] . "<br><br>";
            }


        ?>
    </div>
</html>
