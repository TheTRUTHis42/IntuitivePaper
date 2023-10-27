<html lang = "en">
<head>
    <title>ARX-IF Database Search</title>
    <style>
        body{
            font-family: Helvetica;
        }
        select{
            width: 150px;
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
echo $sql;

?>

<!--title-->
<div><h1>Search for Papers</h1></div>

<!--search form-->
<div id="form">
<form action = results.php method="GET">
    <!-- Search bar -->
        <input type="text" name="search_title" placeholder="Search by title">
        <input type="text" name="author" placeholder="Author">
        <input type="text" name="doi" placeholder="Search by doi">
        <select name="categories">
            <option value="ALL">All Categories</option>
            <?php
            // Assuming $results is the result set from your SQL query
            while ($currentrow = $results->fetch_assoc()) {
                echo "<option value='" . $currentrow['categories'] . "'>" . $currentrow['categories'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Search">
    </form>
</div>


</body>
</html>

