<?php session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['name'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'facultylogin.php';
            }
          </script>";
    // Stop execution
    exit();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .table-container {
            padding: 10px; /* Add padding to create distance from the sides */
            
        }

        table {
            width: 100%;
            
            border-radius: 8px;
            overflow: hidden;
            
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #324897;
            color: white;
            text-align: center !important;

        }
/* Style even rows */
tr:nth-child(even) td {
    background-color: #f2f2f2; /* Grey background color for even rows */
}
        @media only screen and (max-width: 600px) {
            table, th, td {
                border: 1px solid #dddddd;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Include the header and sidebar
    include 'adminheader.php';
    include 'sidebar.php'; 
    
    // Include the database connection
    include 'db.php';
    
    // Check if the 'id' parameter is set in the URL
    if(isset($_GET['id'])) {
        // Retrieve the id value from the URL
        $id = $_GET['id'];

        // Prepare SQL statement to select details from the student table based on the id
        $sql = "SELECT * FROM student WHERE slt= $id";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if the query was successful
        if($result && mysqli_num_rows($result) > 0) {
            // Display the student details in a table
            echo "<div class='table-container' style=\"max-width: 800px; margin: 0 auto;padding: 20px;border:  2px solid transparent;margin-top:10px;border-radius: 30px !important;    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);\">"; // Adding a container div with padding
            echo "<h3 style=\"text-align:center;color:#324897;\">Team Members</h3>";
            echo "<div style='overflow-x:auto;'>";
            echo "<table >";
            echo "<tr><th>Name</th><th>RegNo</th><th>Email</th></tr>";
            
            // Loop through the result set and output data row by row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['regno'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            echo "</div>";
            echo "</div>"; // Closing the container div
        } else {
            // No records found
            echo "<p>No student records found for the provided Service Learning Team ID.</p>";
        }
    }
    
    // Include the footer
    include 'footer.php';
    ?>
</body>
</html>
