<html lang = "en">
<head>
    <title>ARX-IF Database Search</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
        select{
            width: 150px;
            height: 30px;
            border-radius: 10px;
            font-size: large;
            padding-left: 5px;
        }
        input{
            height: 30px;
            border-radius: 10px;
            border-width: 1px;
            font-size: large;
            padding-left: 10px;

        }
        input[type = 'submit']{
            background-color: grey;
            transition: ease .2s;
            color: whitesmoke;
        }
        input:hover[type = submit]{
            background-color: black;
        }
        #form{
            margin: auto;
            width: 30%;
            height: 30%;
            /*background-color: #957DAD;*/
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
var_dump($sql);

?>

<!--title-->
<div id="title"><h1>Search for Papersv2</h1></div>

<!--search form-->
<div id="form">
<form action = results.php method="GET">
    <!-- Search bar -->
        <input type="text" name="search_title" placeholder="Search by title">
    <br> <br>
        <input type="text" name="author" placeholder="Author">
    <br> <br>
        <input type="text" name="doi" placeholder="Search by doi">
    <br> <br>
        <select name="categories">
            <option value="ALL">All Categories</option>
            <?php
            // Assuming $results is the result set from your SQL query
            while ($currentrow = $results->fetch_assoc()) {
                echo "<option value='" . $currentrow['categories'] . "'>" . $currentrow['categories'] . "</option>";
            }
            ?>
        </select>
    <br><br>
        <input type="submit" value="Search">
    </form>
</div>


</body>
</html>

