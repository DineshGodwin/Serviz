<?php
include 'db.php';


$requestId = $_POST['requestId'];

$updateQuery = "DELETE FROM requests_table WHERE request_id = '$requestId'";
$conn->query($updateQuery);


$conn->close();
?>
