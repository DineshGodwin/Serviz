<?php
session_start();
// Include database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
//echo($_POST['submission']);
$gid =$_POST['id'];
//echo("$gid");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected submission type from the form and sanitize input
    $selected_submission = htmlspecialchars($_POST["submission"]);
    $sql = "";

    //echo("$gid");


    switch ($selected_submission) {
        case "Completion Letter":
            $sql = "SELECT * FROM completionletter WHERE gid='$gid'";
            break;
        case "Acceptance Letter":
            $sql = "SELECT * FROM acceptanceletter WHERE gid='$gid'";
            break;
        case "Final Report":
            $sql = "SELECT * FROM finalreport WHERE gid='$gid'";
            break;
        case "Weekly Report":
            $sql = "SELECT Fid, filename, YEAR(timestamp) AS year, DATE_FORMAT(timestamp, '%M') AS month, WEEK(timestamp) AS week FROM weeklyreport WHERE gid='$gid'";
            break;
    }

    // Prepare and execute a parameterized query to fetch data based on the selected submission type
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the table
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>File ID</th><th>File Name</th>";

        // Display additional columns for Weekly Report
        if ($selected_submission === "Weekly Report") {
            echo "<th>Year</th><th>Month</th><th>Week</th>";
        }

        echo "<th>View Submission</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["Fid"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["filename"]) . "</td>";

            // Display additional columns for Weekly Report
            if ($selected_submission === "Weekly Report") {
                echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["month"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["week"]) . "</td>";
            }
            if($selected_submission === "Completion Letter")
            {
                $subdirectory="CompletionLetter";
            }
            elseif($selected_submission==="Acceptance Letter")
            {
                $subdirectory="AcceptanceLetter";
            }
            elseif($selected_submission==="Weekly Report")
            {
                $subdirectory="WeeklyReport";
            }
            else{
                $subdirectory="FinalReport";
            }


echo "<td><a href='reports/$subdirectory/" . htmlspecialchars($row["filename"]) . "' target='_blank'>View</a></td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
