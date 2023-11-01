<?php

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
$delete = "DELETE FROM sub_category";
$deleteresults = $mysql->query($delete);

//create querey that pulls from database
$sql = "SELECT paper_x_category_id, category FROM paper_x_category ";
$sub_category_id = 0;

//save the query into a variable
$results = $mysql->query($sql);
    while ($currentrow = $results->fetch_assoc())  {
        #sql commands that will be used each time results are fetched
        $checksql = "SELECT sub_category FROM sub_category "; #check if the current sub_category exists already
        $insertsql = "INSERT INTO sub_category (sub_category_id, sub_category, primary_category_id) VALUES ";
        $updatesql = "UPDATE paper_x_category SET sub_category_id = ";

        $current = $currentrow['category'];
        echo "Current is ". $current;
        $checksql = $checksql."WHERE sub_category = '". $current."'";
        echo "<br> Checksql is ".$checksql;
        $checkresults = $mysql->query($checksql);
        var_dump($checkresults);
        $rows = $checkresults->num_rows;
        echo "there are". $rows. "rows";

        if (!$rows) {

            $sub_category_id +=1;
            echo "no category found, starting to add" . $current . " to sub category";
            $insertsql = $insertsql . "(".$sub_category_id.",'" . $current."'";
            $updatesql = $updatesql.$sub_category_id." WHERE paper_x_category_id =".$currentrow['paper_x_category_id'];
            echo "<br> updatesql".$updatesql. "and sub_category_id is".$sub_category_id;
            $updateresults = $mysql->query($updatesql);
            if (strpos($current, "q-bio") !== false) {
                $insertsql = $insertsql . ", 4)";
            }else if (strpos($current, "q-fin") !== false) {
                $insertsql = $insertsql . ", 5)";
            }else if (strpos($current, "stat") !== false) {
                $insertsql = $insertsql . ", 6)";
            }else if (strpos($current, "eess") !== false) {
                $insertsql = $insertsql . ", 7)";
            }else if (strpos($current, "econ") !== false) {
                $insertsql = $insertsql . ", 8)";
            }else if (strpos($current, "math") !== false && strpos($current, "math-ph") == false) {
                $insertsql = $insertsql . ", 2)";
            }else {
                $insertsql = $insertsql . ", 1)";
            }
            echo "<br> insertsql is ".$insertsql;
            $insertresult = $mysql->query($insertsql);
        }
        else {

            $updatesql = $updatesql."$sub_category_id WHERE paper_x_category_id =".$currentrow['paper_x_category_id'];
            echo "<br> the else Update sql is ". $updatesql;
            $updateresults = $mysql->query($updatesql);
        }

    }

?>
