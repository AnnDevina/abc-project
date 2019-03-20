<?php

include "db2.php";

$id = $_GET['id'];
$subCategoryQuery = "DELETE FROM sub_categries WHERE subcatg_code = '".$id."' ";

if (mysqli_query($conn, $subCategoryQuery)) {
        header('Location: http://localhost/abc/Admin/subcatg_2.php');
        exit;
    } else {
        echo "Error deleting record";
    }


?>
