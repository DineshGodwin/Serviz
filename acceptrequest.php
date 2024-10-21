<?php
// Assuming you have a database connection, adjust it as needed
include 'db.php';

// Get data from POST request
$name = $_POST['name'];
$userId = $_POST['userId'];
$gid = $_POST['gid'];

// Update student table slt column with current gid
$updateSltQuery = "UPDATE student SET slt = '$gid' WHERE regno = '$userId'";
$conn->query($updateSltQuery);

// Increment the 'no' column in the team table for the corresponding gid
$incrementNoQuery = "UPDATE team SET no = no + 1 WHERE gid = '$gid'";
$conn->query($incrementNoQuery);
$deleteRequestQuery = "DELETE FROM requests_table WHERE user_id = '$userId' AND gid = '$gid'";
$conn->query($deleteRequestQuery);
$insertAcceptsQuery = "INSERT INTO accepts_table (user_id, gid, timestamp) VALUES ('$userId', '$gid', NOW())";
$conn->query($insertAcceptsQuery);




// Additional logic based on your requirements can be added here

// Close connection

$conn->close();
echo "<script>alert('You (User ID: $userId, Username: $name) have been accepted into the team.');</script>";
?>
