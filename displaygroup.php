<?php
session_start();
ob_start();
// Assuming you have a database connection, adjust it as needed
include 'db.php';


// Fetch group information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectSection'])) {
    $selectedSection = $_POST['selectSection'];

    // Query to fetch group information from the database
    $query = "SELECT * FROM team WHERE class = '$selectedSection'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table>
            <tr>
                <th>ID</th>
                <th>Team Leader Name</th>
                <th>No:of Members</th>
                <th>Action</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            // Check if the 'no' column value is less than 5
            $groupId = $row['gid'];
            $userId = $_SESSION['regno']; // Assuming you have the user's ID in session

            $requestQuery = "SELECT * FROM requests_table WHERE user_id = '$userId' AND gid = '$groupId'";
            $requestResult = $conn->query($requestQuery);

            $hasSentRequest = $requestResult->num_rows > 0;
            $isRequestButtonVisible = $row['no'] < 5;

            echo "<tr>
                    <td>{$row['gid']}</td>
                    <td>{$row['teamleader']}</td>
                    <td>{$row['no']}</td>
                    <td class='action-buttons'>
                        <button onclick=\"viewGroup('{$row['gid']}')\">View Group</button>";

            // Display the "Request" button only if 'no' is less than 5
            if ($isRequestButtonVisible&& !$hasSentRequest) {
                echo "<button onclick=\"sendRequest('{$row['gid']}', this)\">Request</button>";
            } else {
                 echo "<button disabled>&#10003;</button>"; // Display tick mark if the user has sent a request
            }

            echo "</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "No data found for the selected section.";
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>
