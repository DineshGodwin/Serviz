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
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        #buttondiv {
    position: absolute;
    top: 50%; /* Place the top edge of the element at the vertical center of the viewport */
    left: 50%; /* Place the left edge of the element at the horizontal center of the viewport */
    transform: translate(-50%, -50%); /* Move the element back by half of its own width and height */
    display: flex;
    flex-direction: column; /* Align buttons in a single vertical line */
    align-items: center; /* Center-align buttons horizontally */
    gap: 10px;
}


button {
    background-color: #324897;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 34px !important;
    cursor: pointer;
    box-sizing: border-box;
    max-width: 200px;
    width: 100%; /* Full width for buttons */
}

#button-container {
    border: 1px solid #dddddd; /* Border style */
    border-radius: 20px; /* Border radius for rounded corners */
    padding: 40px; /* Padding inside the box */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Box shadow */
    display: flex; /* Set display to flex */
    flex-direction: column; /* Align buttons in a single vertical line */
    align-items: center; /* Center-align buttons horizontally */
    gap: 20px; /* Add gap between buttons */
}

@media screen and (max-width: 300px) {
    button {
        width: calc(100% - 20px); /* Adjusted width for smaller screens */
    }
}

        
    </style>
</head>
<body>
<?php include 'adminheader.php'; ?>
<?php include 'sidebar.php'; ?>
<div id='buttondiv'>
<div id="button-container">
<h4 style="text-align:center;color:#324897;">View Activity</h4>
<a href="Members.php?id=<?php echo $_GET['id']; ?>"><button style="width: 200px;">Members</button></a>

<a href="Submissions.php?id=<?php echo $_GET['id']; ?>"><button style="width: 200px;">Submissions</button></a>
<a href="projectsubmission.php?id=<?php echo $_GET['id']; ?>"><button style="width: 200px;">Project Submissions</button></a>
</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
