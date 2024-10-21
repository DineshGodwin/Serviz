

<?php
// Establish database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
$response = array(); // Create a response array

// Check if email exists
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM student WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $response['emailExists'] = true;
    } else {
        $response['emailExists'] = false;
    }
}

// Check if registration number exists
if (isset($_POST['regno'])) {
    $regno = $_POST['regno'];
    $sql = "SELECT * FROM student WHERE regno = '$regno'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $response['regnoExists'] = true;
    } else {
        $response['regnoExists'] = false;
    }
}

// Return JSON response
echo json_encode($response);

$conn->close();
?>
