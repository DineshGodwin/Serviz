<?php
session_start();
ob_start(); // Start output buffering
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
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Class Details</title>
  <style>
    /* Add your CSS styles here */
    table {
    width: calc(100% - 20px); /* Adjusted width to slim down the table */
    border-collapse: collapse;
    border: 2px solid #dddddd; /* Add border */
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Add shadow */
    margin: 10px; /* Add margin to create space between the table and its container */
    padding: 1px !important; /* Add padding to the table */
}

    th, td {
      padding: 8px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #324897;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .update-button {
      background-color: #324897;
      border: none;
      color: white;
      padding: 8px 12px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 14px;
      cursor: pointer;
    }

    .update-button:hover {
      background-color: #45a049;
    }

    .extract-button {
      background-color: #324897;
      border: none;
      color: white;
      padding: 8px 12px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 14px;
      cursor: pointer;
    }

    .extract-button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
<?php include('adminheader.php'); ?>
<div style="text-align: center;color: blue;margin-top:30px;margin-left:50px;margin-right:50px;">
  <form method="post">
    <label for="class-dropdown" >Select Class:</label>
    <select id="class-dropdown" name="class-dropdown">
      <option value="" >Select Class</option>
      <?php
      include 'db.php';


      $sql = "SELECT DISTINCT class FROM team ORDER BY class";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<option value='" . $row["class"] . "'>" . $row["class"] . "</option>";
        }
      } else {
        echo "0 results";
      }

      $conn->close();
      ?>
    </select>
    <input style="color: #324897; font-weight: bold;" type="submit" value="Submit">
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedClass = $_POST['class-dropdown'];

    include "db.php";

    $sql = "SELECT * FROM team WHERE class = '$selectedClass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table >";
      echo "<tr><th>CLASS</th><th>TEAM LEADER</th><th>REGISTRATION NUMBER </th><th>NUMBER OF MEMBERS</th><th>UPDATE MARKS</th></tr>";
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["class"] . "</td>";
          echo "<td>" . $row["teamleader"] . "</td>";
          echo "<td>" . $row["tlreg"] . "</td>";
          echo "<td>" . $row["no"] . "</td>";
        
          // Fetch regno and gid from the database based on class
          $class = $selectedClass;
          $gid = $row["gid"]; // Retrieve Gid directly from the result
          if (!empty($gid)) {
              echo "<td><a href='individualupdate.php?gid=$gid' class='update-button'>Individual Update</a></td>";
          } else {
              echo "<td colspan='2'>No students found</td>";
          }
          echo "</tr>";
      }
      echo "</table>";
      // Add Extract button below the table
      echo "<a href='extact_file.php?selected-class=" . urlencode($selectedClass) . "' class='extract-button'>Extract Marks</a>";
  } else {
      echo "0 results";
  }
  

    $conn->close();
  }
  ?>
  <?php include('footer.php'); ?>
</body>
</html>
<?php
ob_end_flush(); // Flush output buffer and send content to the browser
?>