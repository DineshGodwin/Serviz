<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>File Upload Form</title>
</head>
<body>

<h2>File Upload Form</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
  <label for="file">Choose a file:</label>
  <input type="file" name="file" id="file"><br><br>
  
  <label for="submission">Select submission type:</label>
  <select name="submission" id="submission">
    <option value="Completion Letter">Completion Letter</option>
    <option value="Acceptance Letter">Acceptance Letter</option>
    <option value="Final Report">Final Report</option>
    <option value="Weekly Report">Weekly Report</option>
  </select><br><br>
  
  <input type="submit" value="Submit">
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
    $tableName = ucfirst($submissionType); // Capitalize first letter
    $uploadDirectory .= $submissionType . "/";

    // Create directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create directory recursively
    }

    $fileName = basename($file["name"]);
    $targetPath = $uploadDirectory . $fileName;

    // Check if file already exists
    if (file_exists($targetPath)) {
        echo "Sorry, file already exists.";
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            // File uploaded successfully
            echo "The file ". htmlspecialchars($fileName). " has been uploaded.";

            // Insert file information into database
            $sql = "INSERT INTO {$tableName} (filename, folderpath, timestamp, gid) VALUES (?, ?, NOW(), 1)";
            
            // Prepare SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ss", $fileName, $targetPath);

// Execute query
if ($stmt->execute()) {
    echo "Record inserted successfully into {$tableName} table.";
} else {
    echo "Error: " . $stmt->error;
}


            // Close statement
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "No file uploaded or submission type selected.";
}

// Close the database connection
$conn->close();
?>
