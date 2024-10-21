<?php
// getTeamMembers.php

// Include connection.php or any necessary files
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["gid"])) {
    $groupId = $_GET["gid"];

    // Fetch team members based on the group ID
    $memberSql = "
    SELECT rs.regno, rs.name, rs.email, rs.slt, st.class
    FROM student rs
    JOIN team st ON st.gid = rs.slt
    WHERE st.gid = '$groupId'";

    $memberResult = $conn->query($memberSql);

    if ($memberResult === false) {
        echo json_encode(["error" => "Error executing query: " . $conn->error]);
        exit;
    }

    // Extract member details and return as JSON
    $memberDetails = ($memberResult !== false && $memberResult->num_rows > 0) ? $memberResult->fetch_all(MYSQLI_ASSOC) : [];

    echo json_encode($memberDetails);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
