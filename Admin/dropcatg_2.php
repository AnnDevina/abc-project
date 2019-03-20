<?php
// sql to delete a record
$sql = "DELETE FROM MyGuests WHERE id=3";

if ($conn->query($sql) === TRUE) {
echo "Record deleted successfully";
} else {
echo "Error deleting record: " . $conn->error;
}
?>

<?php

if(isset($_POST['DROP']))
{
    $sql = "DELETE FROM categries
    WHERE catg_code =  ('".$_POST["categorycode"]."')";

    /*if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo "New record created successfully. Last inserted ID is: " . $last_id;
    }*/
    //mysqli_insert_id($conn);

    $result = mysqli_query($conn,$sql);
}

?>
