<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
include 'db.php';


// Fetch gid from the URL
if(isset($_GET['id'])) {
    $gid = $_GET['id'];
} else {
    // Handle case when gid is not provided in URL
    // You may want to redirect or show an error message
}

// Fetch project title and description from the database
$fetchProjectSql = "SELECT projtitle, projdesc FROM idea WHERE gid = '$gid' ORDER BY id DESC LIMIT 1";
$projectResult = $conn->query($fetchProjectSql);

if ($projectResult->num_rows > 0) {
    $projectRow = $projectResult->fetch_assoc();
    $projectTitle = $projectRow["projtitle"];
    $projectDescription = $projectRow["projdesc"];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $comment = $_POST["comment"];

    // Handle approve or reject action
    if (isset($_POST["approve"])) {
        // Update status column to 1
        $updateSql = "UPDATE idea SET status = 1 WHERE gid = '$gid'";
        if ($conn->query($updateSql) === TRUE) {
            // Status updated successfully
            $approvalMessage = "idea approved successfully.";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } elseif (isset($_POST["reject"])) {
        // Update status column to 0
        $updateSql = "UPDATE idea SET status = 0 WHERE gid = '$gid'";
        if ($conn->query($updateSql) === TRUE) {
            // Status updated successfully
            $rejectionMessage = "Idea rejected successfully.";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    }

    // Check if comment is provided and update the comment column
    if (!empty($comment)) {
        $updateCommentSql = "UPDATE idea SET comment = '$comment' WHERE gid = '$gid'";
        if ($conn->query($updateCommentSql) === TRUE) {
            // Comment updated successfully
            $commentMessage = "Comment added successfully.";
        } else {
            echo "Error updating comment: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Idea</title>
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
            margin-bottom: 40px; /* Adjusted margin-bottom */
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #324897;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
            text-align: left;
            padding: 60px;
            margin-top: 20px; /* Adjusted margin-top */
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
        }

        #ideaStatus {
            margin-top: 20px;
            margin-bottom: 40px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            text-align: center;
        }

        #ideaStatus p {
            margin: 0;
            font-weight: bold;
            color: #333;
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

        #ideaStatus {
            margin-top: 20px;
            margin-bottom: 40px;
            padding: 10px;
        }
    }
    </style>
</head>
<body>


    <?php include 'adminheader.php';?> 

    <?php include 'sidebar.php'; ?>

<div class="main-content">
    <form  method="post">
        <h2>Project Details</h2>

        <label for="projectTitle">Project Title:</label>
<span id="projectTitle"><?php echo isset($projectTitle) ? $projectTitle : ''; ?></span><br>

<label for="projectDescription">Project Description:</label>
<span id="projectDescription"><?php echo isset($projectDescription) ? $projectDescription : ''; ?></span><br>

<!-- Add comment section -->
<label for="comment">Comment:</label>
<textarea id="comment" name="comment" rows="2"></textarea>


        <!-- Replace submit button with approve and reject buttons -->
        <button type="submit" name="approve" value="approve">Approve</button>
        <button type="submit" name="reject" value="reject">Reject</button>
    </form>

    <?php
    if(isset($approvalMessage)) {
        echo "<div id='ideaStatus'><p>$approvalMessage</p></div>";
    }
    if(isset($rejectionMessage)) {
        echo "<div id='ideaStatus'><p>$rejectionMessage</p></div>";
    }
    if(isset($commentMessage)) {
        echo "<div id='ideaStatus'><p>$commentMessage</p></div>";
    }
    ?>
</div>


    <?php include 'footer.php';?>


</body>
</html>
