<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc";

$cattitle = filter_input(INPUT_POST,'categorytitle');

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";


?>



<?php

if(isset($_POST['save']))
{
    $sql = "INSERT INTO category (cat_title)
    VALUES ('".$_POST["cat_title"]."')";

    $result = mysqli_query($conn,$sql);
}

?>

<form action="myCategory.php" method="post">

    <label id="cat_title">Cat Title</label><br/>
    <input type="cat_title" name="cat_title"><br/>


    <button type="submit" name="save">save</button>

</form>

