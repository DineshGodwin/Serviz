<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["class"])) {
    $selectedClass = $_GET["class"];

    // Fetch student details based on the selected class from the database
    $sql = "SELECT gid, teamleader, tlreg, class, no FROM team WHERE class = '$selectedClass'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(["error" => "Error executing query: " . $conn->error]);
        exit;
    }

    // Check if there are any rows returned by the query
    if ($result->num_rows > 0) {
        $teams = [];

        while ($row = $result->fetch_assoc()) {
            $groupId = $row["gid"];
            $teamLeader = $row["teamleader"];

            // If the group ID and team leader combination is not in the array, add it
            if (!isset($teams[$groupId][$teamLeader])) {
                $teams[$groupId][$teamLeader] = [];
            }

            // Add the student details to the array
            $teams[$groupId][$teamLeader][] = $row;
        }

        echo json_encode(["teams" => $teams]);
    } else {
        // No records found for the selected class
        echo json_encode(["error" => "No records found for the selected class"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
