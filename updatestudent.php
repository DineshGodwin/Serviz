<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["regno"]) && isset($_POST["gid"])) {
    $regNo = $_POST["regno"];
    $gid = $_POST["gid"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Check if the student is a team leader
    $teamLeaderSql = "SELECT * FROM team WHERE tlreg = ?";
    $teamLeaderStmt = $conn->prepare($teamLeaderSql);
    $teamLeaderStmt->bind_param("s", $regNo);
    $teamLeaderStmt->execute();
    $teamLeaderResult = $teamLeaderStmt->get_result();

    if ($teamLeaderResult->num_rows > 0) {
        // Update both student and team table
        $updateStudentSql = "UPDATE student SET name = ?, email = ? WHERE regno = ?";
        $updateStudentStmt = $conn->prepare($updateStudentSql);
        $updateStudentStmt->bind_param("sss", $name, $email, $regNo);

        $updateTeamSql = "UPDATE team SET teamleader = ? WHERE tlreg = ?";
        $updateTeamStmt = $conn->prepare($updateTeamSql);
        $updateTeamStmt->bind_param("ss", $name, $regNo);

        // Execute both queries within a transaction
        $conn->begin_transaction();
        $studentUpdated = $updateStudentStmt->execute();
        $teamUpdated = $updateTeamStmt->execute();
        
        if ($studentUpdated && $teamUpdated) {
            $conn->commit();
            $_SESSION['student_updated'] = true; // Set session variable to true upon successful update
            echo '<script>alert("Records were updated successfully.");</script>';
            echo '<script>window.history.go(-2);</script>'; // Go back two pages
            exit;
        } else {
            $conn->rollback();
            echo "Error updating student or team record: " . $conn->error;
        }
    } else {
        // Update only student table for team members
        $updateStudentSql = "UPDATE student SET name = ?, email = ? WHERE regno = ? AND slt = ?";
        $updateStudentStmt = $conn->prepare($updateStudentSql);
        $updateStudentStmt->bind_param("ssss", $name, $email, $regNo, $gid);
        
        if ($updateStudentStmt->execute()) {
            $_SESSION['student_updated'] = true; // Set session variable to true upon successful update
            echo '<script>alert("Records were updated successfully.");</script>';
            echo '<script>window.history.go(-2);</script>'; // Go back two pages
            exit;
        } else {
            echo "Error updating student record: " . $conn->error;
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["regno"]) && isset($_GET["gid"])) {
    // If accessed via GET with regno and gid parameters, redirect to editstudent.php
    $regNo = $_GET["regno"];
    $gid = $_GET["gid"];
    header("Location: editstudent.php?regno=$regNo&gid=$gid");
    exit;
} else {
    // Handle invalid request, redirect to an error page, or display an error message
    echo "Invalid request";
    echo "bye";
    exit;
}
?>
