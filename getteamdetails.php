<?php
// Assuming you have a connection, adjust it as needed
include 'db.php';

// Check if the request contains a group ID
if (isset($_POST['groupId'])) {
    $groupId = $_POST['groupId'];

    // Query to fetch team details from the database based on the group ID
    $query = "SELECT name, regno FROM student WHERE slt = '$groupId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $teamDetails = "<table>";
        $i = 0;

        $teamDetails .= "<tr>";
        $teamDetails .= "<th>#</th>";
        $teamDetails .= "<th>Name</th>";
        $teamDetails .= "<th>Register Number</th>";
        $teamDetails .= "</tr>";

        while ($row = $result->fetch_assoc()) {
            $teamDetails .= "<tr>";
            $teamDetails .= "<td>" . ($i + 1) . "</td>";
            $teamDetails .= "<td>" . htmlspecialchars($row['name']) . "</td>";
            $teamDetails .= "<td>" . htmlspecialchars($row['regno']) . "</td>";
            $teamDetails .= "</tr>";
            $i++;
        }

        $teamDetails .= "</table>";
        echo $teamDetails;
    } else {
        echo "Team details not found.";
    }
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>