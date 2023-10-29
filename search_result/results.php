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
$titleSql = "SELECT * FROM xie_import_6000 ";
$authorSql = "SELECT * FROM xie_import_6000 ";
$categorySql = "SELECT * FROM paper_x_user ";
$DOISql = "SELECT * FROM xie_import_6000 ";


//get search results
$searchTitle = $_REQUEST['search_title'];
$searchAuthor = $_REQUEST['author'];
$searchDOI = $_REQUEST['doi'];
$searchCategory = $_REQUEST['categories'];

//filters
if($searchTitle != ''){
    $titleSql.= "WHERE title LIKE '%". $searchTitle. "%'";
}if($searchAuthor!= ''){
    $authorSql.= "WHERE authors LIKE ". $searchAuthor. "%'";
}if($searchDOI!= ''){
    $DOISql.= "WHERE doi = ". $searchDOI;
}if($searchCategory != "ALL"){
    $categorySql.= "WHERE categories = '". $searchCategory. "'";
}
//store results in a appropriate variables variable
$titleResults = $mysql->query($titleSql);
$authorResults = $mysql->query($authorSql);
$categoryResults = $mysql->query($categorySql);
$DOIResults = $mysql->query($DOISql);

//results error
if(!$titleResults){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $titleSql. "</hr>";
}if(!$authorResults){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $authorSql. "</hr>";
}if(!$categoryResults){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $categorySql. "</hr>";
}if(!$DOIResults){
    echo "<hr>SQL Error:". $mysql->error. "<br>";
    echo "Output SQL:". $DOISql. "</hr>";
}

// Display search terms and record count
echo "<div id='title'>";
echo "<h1>Search Results</h1>";
if($searchTitle !=''){
    echo "You searched for: <strong>$searchTitle</strong><br>";
    echo "Number of records found: <strong>" . $titleResults->num_rows . "</strong><br><br>";

}if($searchAuthor !=''){
    echo "You searched for: <strong>$searchAuthor</strong><br>";
    echo "Number of records found: <strong>" . $authorResults->num_rows . "</strong><br><br>";

}if($searchDOI !=''){
    echo "You searched for: <strong>$searchDOI</strong><br>";
    echo "Number of records found: <strong>" . $DOIResults->num_rows . "</strong><br><br>";

}

echo "</div>";

?>
</body>
<!--display results-->
    <div id="container">
        <?php
            while($Tcurrentrow = mysqli_fetch_assoc($titleResults)) {
                echo "<div class='fields'><strong>title:</strong> ";
                echo $Tcurrentrow['title']. "<br>";
            }
//            while($Acurrentrow = mysqli_fetch_assoc($authorResults)){
//                echo "<strong>DOI:</strong>". $Acurrentrow['authors']. "<br>";
//
//            }
//            while($Ccurrentrow = mysqli_fetch_assoc($categoryResults)) {
//                echo "<strong>Author:</strong>" . $Ccurrentrow['category'] . "<br>";
//            }
//            while($DOIcurrentrow = mysqli_fetch_assoc($DOIResults)) {
//                echo "<strong>Author:</strong>" . $DOIcurrentrow['doi'] . "<br>";
//            }


        ?>
    </div>
</html>
