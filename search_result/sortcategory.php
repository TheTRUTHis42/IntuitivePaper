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
//$delete = "DELETE FROM sub_category";
//$deleteresults = $mysql->query($delete);

//create querey that pulls from database
$sql = "SELECT paper_x_category_id, category, sub_category_id FROM paper_x_category ";
$sub_category_id = 0;

//save the query into a variable
$results = $mysql->query($sql);
    while ($currentrow = $results->fetch_assoc())  {
        #sql commands that will be used each time results are fetched
        $checksql = "SELECT sub_category_id, sub_category FROM sub_category ";
        #check if the current sub_category exists already, WHERE clause coming up later in the code to define what the category is
        $insertsql = "INSERT INTO sub_category (sub_category_id, sub_category, prim_category_id) VALUES ";
        #put the info of this sub category in to the sub_category table, values yet to be specified
        $updatesql = "UPDATE paper_x_category SET sub_category_id = "; #update the sub_category_id in the paper_x_vategory table

        $current = $currentrow['category']; #give a new name for category from paper_x_category table
        echo "<br><br>Current is ". $current;
        $currentid = $currentrow["sub_category_id"];
        echo "currentid is ".$currentid;
        $checksql = $checksql."WHERE sub_category = '". $current."'";
        $paper_x_category_id = $currentrow['paper_x_category_id'];
        echo "paper_x_category_id is".$paper_x_category_id;

        echo "<br> Checksql is ".$checksql;
        $checkresults = $mysql->query($checksql); #run checksql query
        var_dump($checkresults);
        $rows = $checkresults->num_rows;
        echo "there are ". $rows. " rows";



        if (!$rows) {

            $sub_category_id +=1;

            $updatesql = $updatesql.$sub_category_id." WHERE paper_x_category_id =".$paper_x_category_id;
            echo "<br> updatesql".$updatesql. "and sub_category_id is".$sub_category_id;
            $updateresults = $mysql->query($updatesql);

            echo "no category found, starting to add" . $current . " to sub category";
            $insertsql = $insertsql . "(".$sub_category_id.",'" . $current."'"; # add values for sub_category_id and sub_category, needing to check category

            if (strpos($current, "CoRR") !== false) {
                $insertsql = $insertsql . ", 3)";
            }else if (strpos($current, "q-bio") !== false) {
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
            echo "<br> running insertsql ".$insertsql;
            $insertresult = $mysql->query($insertsql);
        }
        else {
            echo ("there are more than 0 rows, meaning this sub category is already recorded");

            $checkinfo = $checkresults -> fetch_assoc();
            var_dump($checkinfo["sub_category_id"]);
            echo "<strong>updating sub_category_id now!</strong>";
            $updatesql = $updatesql.$checkinfo["sub_category_id"]." WHERE paper_x_category_id =".$paper_x_category_id;
            echo "updatesql is ".$updatesql;
            $updateresults = $mysql->query($updatesql);
//            var_dump($checkinfo["sub_category_id"]);
//            $updatesql = $updatesql.$checkinfo["sub_category_id"]." WHERE paper_x_category_id =".$paper_x_category_id;
//            echo "<br> the else Update sql is ". $updatesql;
//            $updateresults = $mysql->query($updatesql);

        }

    }


?>
