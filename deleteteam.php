<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["gids"])) {
    // Extract group IDs from the POST data
    $gids = json_decode($_POST["gids"], true);

    // Sanitize each group ID to prevent SQL injection
    $sanitizedGids = array_map(function ($gid) use ($conn) {
        return mysqli_real_escape_string($conn, $gid);
    }, $gids);

    // Start a transaction
    mysqli_autocommit($conn, false);

    $success = true;

    // Delete team members from the student table
    foreach ($sanitizedGids as $gid) {
        // Construct the SQL query to delete team members (including the team leader) from the student table
        $deleteTeamMembersSql = "DELETE FROM student WHERE slt = '$gid'";

        // Execute the delete query for team members
        if ($conn->query($deleteTeamMembersSql) !== TRUE) {
            $success = false;
            break; // Break the loop if an error occurs
        }
    }

    // Delete team leader from the team table
    if ($success) {
        foreach ($sanitizedGids as $gid) {
            // Construct the SQL query to delete the team leader from the team table
            $deleteTeamLeaderSql = "DELETE FROM team WHERE gid = '$gid'";

            // Execute the delete query for team leader
            if ($conn->query($deleteTeamLeaderSql) !== TRUE) {
                $success = false;
                break; // Break the loop if an error occurs
            }
        }
    }

    if ($success) {
        // Commit the transaction
        mysqli_commit($conn);
        // Return a success message
        echo "Teams deleted successfully.";
    } else {
        // Rollback the transaction
        mysqli_rollback($conn);
        // Return an error message
        echo "Error deleting teams.";
    }

    // Restore autocommit mode
    mysqli_autocommit($conn, true);

} else {
    // Return an error message if the request is invalid
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
