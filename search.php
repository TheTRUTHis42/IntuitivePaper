<html lang = "en">
<head>
    <title>ARX-IF Database Search</title>
    <style>
        body{
            font-family: Helvetica;
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
    "louisexie_IPImportTest"
);

//connection error test
if($mysql->connect_errno){
    echo "Database Connection Error:". $mysql->connect_errno;
    exit();
}
//createquerey that pulls from database
$sql = "SELECT * FROM xie-import_6000 ORDER BY ";

//save the query into a variable
$results = $mysql->query($sql);

?>

<!--title-->
<div><h1>Search for Papers</h1></div>

<!--serach form-->
<div id="form">
    <form action = results.php>
<!--search bar-->
        <input type = 'text' name = 'search_title' placeholder="search by title">
        <input type = 'text' name="author" placeholder="author">
        <input type = 'text' name="doi" placeholder="search by doi">
        <select name = 'categories'>
            <option name="ALL">All Categories</option>
            <?php
            while($currentrow = $results->fetch_assoc()){
                echo "<option". $currentrow['categories']."</option>";
            }
            ?>
            ?>
        </select>
        <a><input type = "submit"</a>

<!--potential popular searches-->
        <select name = 'popular_searches'>
            <?php
            $sql = "SELECT * FROM xie_import_6000"
            ?>
        </select>
    </form>
</div>

</body>
</html>

