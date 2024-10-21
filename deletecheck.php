<?php
session_start();

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["regnos"])) {
    // Handling multiple deletions
    $regnos = json_decode($_POST["regnos"]);

    // Check if any team leader is selected for deletion
    $teamLeaderSelected = false;
    foreach ($regnos as $regno) {
        $teamLeaderCheckSql = "SELECT tlreg FROM team WHERE tlreg = ?";
        $teamLeaderCheckStmt = $conn->prepare($teamLeaderCheckSql);
        $teamLeaderCheckStmt->bind_param("s", $regno);
        $teamLeaderCheckStmt->execute();
        $teamLeaderCheckResult = $teamLeaderCheckStmt->get_result();
        if ($teamLeaderCheckResult->num_rows > 0) {
            $teamLeaderSelected = true;
            break;
        }
    }

    if ($teamLeaderSelected) {
        // Delete entire team if a team leader is selected
        $deleteTeamSql = "DELETE FROM student WHERE slt IN (SELECT gid FROM team WHERE tlreg IN (?" . str_repeat(",?", count($regnos) - 1) . "))";
        $deleteTeamStmt = $conn->prepare($deleteTeamSql);
        $deleteTeamStmt->bind_param(str_repeat("s", count($regnos)), ...$regnos);
        if ($deleteTeamStmt->execute()) {
            $deleteTeamLeaderSql = "DELETE FROM team WHERE tlreg IN (?" . str_repeat(",?", count($regnos) - 1) . ")";
            $deleteTeamLeaderStmt = $conn->prepare($deleteTeamLeaderSql);
            $deleteTeamLeaderStmt->bind_param(str_repeat("s", count($regnos)), ...$regnos);
            if ($deleteTeamLeaderStmt->execute()) {
                // No need to update count if team leader is deleted
                header("Location: viewteam.php"); 
                exit;
            } else {
                $_SESSION['delete_error'] = "Error deleting team leader: " . $conn->error;
            }
        } else {
            $_SESSION['delete_error'] = "Error deleting team: " . $conn->error;
        }
    } else {
        // Delete individual records if no team leader is selected
        $deleteSql = "DELETE FROM student WHERE regno IN (?" . str_repeat(",?", count($regnos) - 1) . ")";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param(str_repeat("s", count($regnos)), ...$regnos);
        if ($deleteStmt->execute()) {
            // Update count in the team table
            $updateTeamCountSql = "UPDATE team SET no = (SELECT COUNT(*) FROM student WHERE slt = team.gid)";
            if ($conn->query($updateTeamCountSql) === TRUE) {
                $_SESSION['count_updated'] = "Count updated successfully.";
            } else {
                $_SESSION['delete_error'] = "Error updating count: " . $conn->error;
            }
            header("Location: viewteam.php"); 
            exit;
        } else {
            $_SESSION['delete_error'] = "Error deleting records: " . $conn->error;
        }
    }
} else {
    // Redirect to an error page or display an error message for invalid requests
    $_SESSION['delete_error'] = "Invalid request";
    header("Location: viewteam.php"); 
    exit;
}
?>
