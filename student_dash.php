<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Idea</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* Align children in a column */
            min-height: 100vh;
            
        }

        .header,
        .footer {
            background-color: #333;
            color: #fff;
            padding: 0px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #324897;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            
            margin: 0 auto;
            text-align: left;
            padding: 30px;
            padding-bottom:0px;
            margin-top: 70px; /* Adjusted margin-top */
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, textarea {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #324897;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            float: center;
            border-radius: 32px ! important;
        }

        #ideaStatus {
     

            text-align: center;
            
        }



    @media screen and (max-width: 768px) {
        .header,
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
        }

        .main-content {
            width: 100%;
            margin-top: 100px; /* Adjusted margin-top */
        }

        form {
            padding: 20px;
        }

    }


    </style>
</head>
<body>

<?php
include 'db.php';


$gid = $_SESSION['slt'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $projectTitle = $_POST["projectTitle"];
    $projectDescription = $_POST["projectDescription"];
    $communityName = $_POST["communityName"];

    // Get the current year
    $currentYear = date("Y");

    $selectQuery = "SELECT COUNT(*) as count FROM idea WHERE gid = '$gid'";
    $result = mysqli_query($conn, $selectQuery);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];
    
    if ($count > 0) {
        // If a record with the same gid exists, delete the previous record
        $deleteQuery = "DELETE FROM idea WHERE gid = '$gid'";
        mysqli_query($conn, $deleteQuery);
    }
    
    // SQL query to insert data into the table
    $insertQuery = "INSERT INTO idea (gid, projtitle, projdesc, community, year)
                    VALUES ('$gid', '$projectTitle', '$projectDescription', '$communityName', '$currentYear')";
    
    // Execute the insert query
    if (mysqli_query($conn, $insertQuery)) {
        echo "Record inserted successfully.";
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}

// Fetch status and comment from the database
$fetchStatusSql = "SELECT status, comment FROM idea WHERE gid = '$gid' ORDER BY id DESC LIMIT 1";
$statusResult = $conn->query($fetchStatusSql);

if ($statusResult->num_rows > 0) {
    $statusRow = $statusResult->fetch_assoc();
    $status = $statusRow["status"];
    $comment = $statusRow["comment"];
}

?>

<div class="header">
    <?php include 'header.php'; ?>
</div>

<?php include 'student_sidebar.php'; ?>

<div class="main-content">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Submit Your Idea</h2>

        <label for="projectTitle">Project Title:</label>
        <input type="text" id="projectTitle" name="projectTitle" required>

        <label for="projectDescription">Project Description:</label>
        <textarea id="projectDescription" name="projectDescription" rows="4" required></textarea>

        <label for="communityName">Community Name:</label>
        <input type="text" id="communityName" name="communityName" required>

        <button type="submit">Submit</button>
        <?php 
if (isset($status)) { 
    echo '<div id="ideaStatus">'; 
    if ($status == 0) {
        echo "<p><p>Status: <span style=color:red;>Declined</span></p></p>";
    } elseif ($status == 1) {
        echo "<p><p>Status: <span style=color:Green;>Accepted</span></p></p>";
    }
    echo "<p>Comment: $comment</p>";
    echo '</div>';
} else {
    echo '<div id="ideaStatus"><p>Status: <span style=color:Orange;>Pending</span></p></div>';
}
?>
    </form>
   

</div>

<div class="footer">
    <?php include 'footer.php'; ?>
</div>

</body>
</html>
