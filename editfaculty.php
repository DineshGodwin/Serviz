<?php
session_start();
include "db.php";
include "aheader.php";
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'adminlogin.php';
            }
          </script>";
    // Stop execution
    exit();
}
// Initialize variables
$name = $email = $classassg = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"]; // Retrieve the ID from the form submission, but don't update it
    $name = $_POST["name"];
    $email = $_POST["email"];
    $classassg = $_POST["classassg"];

    $sql = "UPDATE faculty SET name='$name', email='$email', classassg='$classassg' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Record updated successfully
        echo '<script>alert("Record updated successfully"); window.location.href = "managefaculty.php";</script>';
        exit; // Stop further execution
    } else {
        echo "Error updating record: " . $conn->error;
    }
} elseif (isset($_GET["id"])) {
    // Fetch existing data if the ID is provided in the URL
    $id = $_GET["id"];

    $selectSql = "SELECT * FROM faculty WHERE id=$id";
    $result = $conn->query($selectSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $email = $row["email"];
        $classassg = $row["classassg"];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Details</title>
    <style>
        form {
            border: 1px solid #324897;
            border-radius: 31px;
            width: 400px;
            height: 420px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top:120px;
        }

        label {
            float: left;
            width: 80%; /* Set the width of the label */
            margin: 10px 0;
        }

        input {
            width: 80%;
            padding: 7px;
            margin: 10px 0;
            border-radius: 30px;
            border: 1px solid #324897;
            justify-content: center;
        }

        .button {
            width: 80%;
            padding: 10px;
            margin: 20px 0; /* Add margin to create space between the input and the button */
            border-radius: 30px;
            border: 1px solid #324897;
            color: white;
            background-color: #324897;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .view {
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
            margin-top:100px;
            /* Add margin to separate from the table */
            width: 90px;
        }
    </style>
</head>
<body>
    <div class="container">
    <button class="view" onclick="goBack()">Go Back</button>
    <form method="post">
        <!-- Add form fields for editing, excluding the ID -->
        <h4 class="mt-3">Edit Faculty Details</h4>
        <!-- ID field is excluded -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="name">Enter name</label>
        <input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>">
        <label for="email">Enter email</label>
        <input type="email" name="email" placeholder="Enter email" value="<?php echo $email; ?>">
        <label for="classassg">Enter Class Assigned</label>
        <input type="text" name="classassg" placeholder="Enter Class assigned"  value="<?php echo $classassg; ?>">
        <input type="submit" value="Update" name="button" class="button">
    </form>
    </div>
    <?php
    include 'footer.php';
    ?>
<script>    
    function goBack() {
    window.location.href = "managefaculty.php";
        
    }</script>
</body>
</html>
