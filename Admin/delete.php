<?php

include "db2.php";
$id = $_GET['id'];

$categoryQuery=  "DELETE FROM categries WHERE catg_code = '".$id."'";
$subCategoryQuery = "DELETE FROM sub_categries WHERE p_catg_code = '".$id."' ";
    if (mysqli_query($conn, $subCategoryQuery)) {
        if (mysqli_query($conn, $categoryQuery)) {
            header('Location: http://localhost/abc/Admin/main_cat2.php');
            exit;
        } else {
            echo "Error deleting record";
        }
    } else {
        echo "Error deleting record";
    }
?>
