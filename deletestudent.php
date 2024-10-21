<?php
// Include the connection file
session_start();
include "db.php";

// Check if the request method is POST and either the regnos or regno parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["regnos"]) || isset($_POST["regno"]))) {

    // Check if regno parameter is set for individual deletion
// Check if regno parameter is set for individual deletion
if (isset($_POST["regno"])) {
    $regno = $_POST["regno"];
    $gid = $_POST["gid"];
    
    // Check if the given regno corresponds to a team leader
    $checkTeamLeaderSql = "SELECT tlreg FROM team WHERE tlreg = '$regno'";
    $teamLeaderCheckResult = $conn->query($checkTeamLeaderSql);

    if ($teamLeaderCheckResult !== false && $teamLeaderCheckResult->num_rows > 0) {
        // If the given regno corresponds to a team leader, delete the entire team
        $deleteTeamSql = "DELETE FROM student WHERE slt ='$gid'";
        if ($conn->query($deleteTeamSql) === TRUE) {
            $deleteTeamLeaderSql = "DELETE FROM team WHERE tlreg = '$regno'";
            if ($conn->query($deleteTeamLeaderSql) === TRUE) {
                echo "Entire team and team leader deleted successfully";
            } else {
                echo "Error deleting team leader: " . $conn->error;
            }
        } else {
            echo "Error deleting team: " . $conn->error;
        }
    } else {
        // If the given regno does not correspond to a team leader, delete the individual record
        // Construct the SQL query to delete individual record
        $deleteIndividualSql = "DELETE FROM student WHERE regno = '$regno' AND slt = '$gid'";
    
        // Execute the DELETE query
        if ($conn->query($deleteIndividualSql) === TRUE) {
            echo "Record deleted successfully";
            
            // Update the count in the team table
            $updateTeamCountSql = "UPDATE team SET no = (SELECT COUNT(*) FROM student WHERE slt = '$gid') WHERE gid='$gid'";
            if ($conn->query($updateTeamCountSql) === TRUE) {
                echo "Count updated successfully in the team table";
            } else {
                echo "Error updating count in the team table: " . $conn->error;
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}
 elseif (isset($_POST["regnos"])) {
        // Get the regnos parameter and decode it from JSON format
        $regnos = json_decode($_POST["regnos"]);
        $gid = $_POST["gid"];

        // Escape each registration number to prevent SQL injection
        $escapedRegnos = array_map(function ($regno) use ($conn) {
            return mysqli_real_escape_string($conn, $regno);
        }, $regnos);

        // Convert the array of escaped registration numbers into a comma-separated string
        $regnosString = implode("','", $escapedRegnos);

        // Check if the team leader's regno is among the selected records
        $teamLeaderRegno = null;
        foreach ($regnos as $regno) {
            $teamLeaderCheckSql = "SELECT tlreg FROM team WHERE tlreg = '$regno'";
            $teamLeaderCheckResult = $conn->query($teamLeaderCheckSql);
            if ($teamLeaderCheckResult !== false && $teamLeaderCheckResult->num_rows > 0) {
                $teamLeaderRegno = $regno;
                break;
            }
        }

        if ($teamLeaderRegno !== null) {
            // If a team leader is among the selected records, delete the entire team
            // Construct the SQL query to delete the entire team from the student table
            $deleteTeamSql = "DELETE FROM student WHERE slt = '$gid' OR regno IN ('$regnosString')";
            if ($conn->query($deleteTeamSql) === TRUE) {
                // Also, delete the team leader from the team table
                $deleteTeamLeaderSql = "DELETE FROM team WHERE tlreg = '$teamLeaderRegno'";
                if ($conn->query($deleteTeamLeaderSql) === TRUE) {
                    echo "Entire team and team leader deleted successfully";
                } else {
                    echo "Error deleting team leader: " . $conn->error;
                }
            } else {
                echo "Error deleting team: " . $conn->error;
            }
        } else {
            // Execute the DELETE query
            $deleteIndividualSql = "DELETE FROM student WHERE regno IN ('$regnosString')";
            if ($conn->query($deleteIndividualSql) === TRUE) {
                // Construct the SELECT query to count remaining records
                $selectRemainingCountSql = "SELECT COUNT(*) AS count FROM student WHERE regno IN ('$regnosString')";
                
                // Execute the SELECT query
                $selectRemainingCountResult = $conn->query($selectRemainingCountSql);
                
                if ($selectRemainingCountResult) {
                    // Fetch the count from the result
                    $countRow = $selectRemainingCountResult->fetch_assoc();
                    $count = $countRow['count'];
                    
                    // Update the count in the team table
                    $updateTeamCountSql = "UPDATE team SET no = $count WHERE gid='$gid'";
                    echo "Debug: Update SQL: $updateTeamCountSql"; // Debugging output
                    if ($conn->query($updateTeamCountSql) === TRUE) {
                        echo "Count updated successfully in the team table";
                    } else {
                        echo "Error updating count in the team table: " . $conn->error;
                    }
                } else {
                    // Handle query execution error
                    echo "Error executing SELECT query: " . $conn->error;
                }
            } else {
                // Handle deletion query execution error
                echo "Error deleting records: " . $conn->error;
            }
        }
    } else {
        // If neither regnos nor regno parameter is set, return an error message
        echo "Invalid request";
    }
} else {
    // If the request method is not POST or neither regnos nor regno parameter is set, return an error message
    echo "Invalid request";
}
?>
