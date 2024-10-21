<?php
session_start();

// Assuming you have a database connection, adjust it as needed
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['groupId'])) {
    $groupId = $_POST['groupId'];

    // Get the user ID or any identifier (replace 'your_user_id' with the actual identifier)
    $userId = $_SESSION['regno'];

    // Insert the request into the database (replace the table and column names as needed)
    $insertQuery = "INSERT INTO requests_table (gid, user_id) VALUES ('$groupId', '$userId')";
    $result = $conn->query($insertQuery);

    if ($result) {
        echo "Request sent successfully.";
    } else {
        echo "Error sending request: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
