<?php
ob_start();
// Database connection parameters
include 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected class
$selectedClass = $_POST['class'];

// Run your query to fetch data based on the selected class
$sql = "SELECT 
t.teamleader AS Team_Leader,
t.no AS Number_of_Members,
i.projtitle AS Project_Title,
i.projdesc AS Project_Description,
i.community AS Community,
s.name AS Member_Name,
s.regno AS Registration_Number
FROM 
team t
JOIN 
idea i ON t.gid = i.gid
JOIN 
student s ON t.gid = s.slt
WHERE t.class = '$selectedClass';"; // Modify this query according to your database schema and selected class
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output headers so that the file is downloaded rather than displayed
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="exported_data.xls"');

    // Start the Excel file
    echo "<table>";

    // Output the column headers
    echo "<tr><th>Team Leader</th><th>Number of Members</th><th>Project Title</th><th>Project Description</th><th>Community</th><th>Member Name</th><th>Registration Number</th></tr>";

    // Output data rows
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["Team_Leader"]."</td><td>".$row["Number_of_Members"]."</td><td>".$row["Project_Title"]."</td><td>".$row["Project_Description"]."</td><td>".$row["Community"]."</td><td>".$row["Member_Name"]."</td><td>".$row["Registration_Number"]."</td></tr>";
        // Modify column names according to your database schema
    }

    // End the Excel file
    echo "</table>";
} else {
    echo "No data found";
}

// Close the database connection
$conn->close();
?>
