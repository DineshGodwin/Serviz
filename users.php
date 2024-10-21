<?php
// Start the session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['regno'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'studentlogin.php';
            }
          </script>";
    // Stop execution
    exit();
}

// Include necessary files
include 'header.php';
include 'student_sidebar.php';

// Database connection details
include 'db.php';


// Get the gid from the session
$gid = $_SESSION['slt'];

// Fetch student information from the database based on gid
$sql = "SELECT Name, regno, email FROM student WHERE slt = '$gid'";
$result = $conn->query($sql);

// Check if there is a result
if ($result->num_rows > 0) {
    // Output data in table form
    echo '<!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Users</title>
              <style>
                  body {
                      font-family: \'Arial\', sans-serif;
                      background-color: #f2f2f2;
                      margin: 0;
                      padding: 0;
                      display: flex;
                      flex-direction: column; /* Align children in a column */
                      min-height: 10vh;
                  }

                  .main-content {
                      flex: 1;
                      padding: 20px;
                      max-width: 800px;
                      margin: 0 auto;
                      background-color: #fff;
                      border-radius: 8px;
                      border: 2px solid #324897;
                      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                      margin-top: 120px;
                      overflow: auto; /* Enable scrolling if content exceeds max height */
                  }

                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-top: 20px;
                  }

                  th, td {
                      border: 1px solid #ddd;
                      padding: 12px;
                      text-align: left;
                  }

                  th {
                      background-color: #324897;
                      color: #fff;
                  }

                  /* Responsive styles */
                  @media screen and (max-width: 600px) {
                      th, td {
                          padding: 8px;
                          font-size: 12px;
                      }
                  }

                  /* Add your additional styles here */

                  .header,
                  .footer {
                      background-color: #333;
                      color: #fff;
                      padding: 10px;
                      text-align: center;
                  }

                  .student-sidebar {
                      width: 200px;
                      background-color: #eee;
                      padding: 20px;
                  }
              </style>
          </head>
          <body>

              <!-- Display student information -->
              <div class="main-content">
                  <h2>Users</h2>
                  <table>
                      <tr>
                          <th>Name</th>
                          <th>Regno</th>
                          <th>Email</th>
                      </tr>';
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                  <td>' . $row["Name"] . '</td>
                  <td>' . $row["regno"] . '</td>
                  <td>' . $row["email"] . '</td>
              </tr>';
    }
    echo '</table></div>';
} else {
    // Handle the case where no matching record is found
    echo '<p>No student information found.</p>';
}

// Close the database connection
$conn->close();
?>

<!-- Include the footer -->
<?php include 'footer.php'; ?>

</body>
</html>';
