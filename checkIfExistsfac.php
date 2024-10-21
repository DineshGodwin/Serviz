

<?php
// Establish database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
$response = array(); // Create a response array

// Check if email exists
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM faculty WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $response['emailExists'] = true;
    } else {
        $response['emailExists'] = false;
    }
}

// Check if registration number exists


// Return JSON response
echo json_encode($response);

$conn->close();
?>
