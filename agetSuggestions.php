<?php
// Include your database connection file
include "db.php";

// Check if the required parameters are set
if (isset($_GET["column"]) && isset($_GET["searchTerm"])) {
    $column = $_GET["column"];
    $searchTerm = $_GET["searchTerm"];

    // Prepare the SQL query
    $sql = "SELECT DISTINCT $column FROM idea WHERE $column LIKE CONCAT(?, '%')";

    // Add an additional condition to check for titles starting with 'a'
    if ($searchTerm === 'a') {
        $sql .= " AND $column LIKE 'a%'";
    }

    // Prepare a statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("s", $searchTerm);

        // Execute the statement
        if ($stmt->execute()) {
            // Bind result variables
            $stmt->bind_result($result);

            // Fetch values and store them in an array
            $suggestions = [];
            while ($stmt->fetch()) {
                $suggestions[] = $result;
            }

            // Close statement
            $stmt->close();

            // Return JSON response
            echo json_encode($suggestions);
        } else {
            // Error executing the statement
            echo json_encode(["error" => "Error executing the statement"]);
        }
    } else {
        // Error preparing the statement
        echo json_encode(["error" => "Error preparing the statement"]);
    }
} else {
    // Required parameters are missing
    echo json_encode(["error" => "Required parameters are missing"]);
}
?>