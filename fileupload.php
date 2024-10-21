<?php session_start();
include 'header.php'; 
 include 'student_sidebar.php';
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
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>File Upload Form</title>
<style>
  form {
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #324897;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            margin-top:200px !important;
            margin: 0 auto;
            text-align: center;
            padding: 30px;
            
            margin-top: 70px; /* Adjusted margin-top */
        }
        #submit {
            background-color: #324897;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            float: center;
            border-radius: 32px ! important;
        }

</style>
</head>
<body>
<div id="container">


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
<h2 style="margin-top:0px;">File Upload Form</h2>  
<label for="file">Choose a file:</label>
  <input type="file" name="file" id="file"><br><br>
  
  <label for="submission">Select submission type:</label>
  <select name="submission" id="submission">
    <option value="Completion Letter">Completion Letter</option>
    <option value="Acceptance Letter">Acceptance Letter</option>
    <option value="Final Report">Final Report</option>
    <option value="Weekly Report">Weekly Report</option>
  </select><br><br>
  
  <input  id="submit" type="submit" value="Submit">
 
</form>

<?php
// Display all PHP errors, warnings, and notices
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Include database connection
include 'db.php';

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && isset($_POST["submission"])) {
    $file = $_FILES["file"];

    // Determine upload directory based on submission type
    $uploadDirectory = "reports/";
    $submissionType = str_replace(' ', '', $_POST['submission']); // Normalize submission type
    $tableName = strtolower($submissionType); // Capitalize first letter
    $uploadDirectory .= $submissionType . "/";

    // Create directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create directory recursively
    }

    $fileName = basename($file["name"]);
    $targetPath = $uploadDirectory . $fileName;
    $gid=$_SESSION['slt'];

    // Check if file already exists
    if (file_exists($targetPath)) {
        echo "<script>alert('Sorry, file already exists.')</script>";
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            // File uploaded successfully
            echo "<script>alert('The file ". htmlspecialchars($fileName). " has been uploaded.')</script>";

            // Insert file information into database
            $sql = "INSERT INTO {$tableName} (filename, folderpath, timestamp, gid) VALUES (?, ?, NOW(), ?)";
           echo "need to make update instead of insert to prevent duplicate entry and wjile update join with idea table since finalreport sno is foriegn";
            // Prepare SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sss", $fileName, $targetPath,$gid);

// Execute query
if ($stmt->execute()) {
   // echo "<script>alert('Record inserted successfully into {$tableName} table.')</script>";
} else {
    echo "Error: " . $stmt->error;
}


            // Close statement
            $stmt->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
        }
    }
} else {
   // echo "No file uploaded or submission type selected.";
}

// Close the database connection
$conn->close();
include 'footer.php'; 
?>
</div>