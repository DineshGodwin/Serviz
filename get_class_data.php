<?php
// Connect to your database (replace with your actual database credentials)

// Check connection
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected class from the form
    $selected_class = $_POST["class"];

    // Prepare and execute a query to fetch data for the selected class
    $sql = "SELECT * FROM team WHERE class = '$selected_class'";
    $result = $conn->query($sql);

    // Display the table
    if ($result->num_rows > 0) {
        echo "<table>";
    
        echo "<tr><th>Team Leader</th><th>No. of Members</th><th>View Group Activity</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["teamleader"] . "</td>";
            echo "<td>" . $row["no"] . "</td>";
            echo "<td><button onclick=\"location.href='view_activity.php?id=" . $row["gid"] . "'\">View Activity</button></td>";
  
            // Add more columns as needed
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }
}

// Close the database connection
$conn->close();
?>
