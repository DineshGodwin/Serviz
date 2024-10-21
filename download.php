<?php
// Include database connection
include "db.php";
// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the project title and sno are provided in the URL
if(isset($_GET['projtitle']) && isset($_GET['sno'])) {
    // Sanitize input
    $projectTitle = sanitize_input($_GET['projtitle']);
    $sno = sanitize_input($_GET['sno']);
    
    // Fetch folderpath from finalreport table based on projtitle and sno
    $sql_final_report = "SELECT finalreport.folderpath 
                         FROM finalreport 
                         INNER JOIN idea ON finalreport.sno = idea.id 
                         WHERE idea.projtitle = ? AND finalreport.sno = ?";
    $stmt_final_report = $conn->prepare($sql_final_report);
    $stmt_final_report->bind_param("si", $projectTitle, $sno);
    $stmt_final_report->execute();
    $result_final_report = $stmt_final_report->get_result();
    
    // If file found, set headers for viewing
    if($result_final_report->num_rows > 0) {
        $row_final_report = $result_final_report->fetch_assoc();
        $folderpath = $row_final_report['folderpath'];
        
        // Define the full file path
        $file = $folderpath;
        
        // Check if the file exists
        if(file_exists($file)) {
            // Set headers for viewing the file in the browser
            header('Content-Type: application/pdf'); // Change to appropriate MIME type if not PDF
            // Output the file content to the browser
            readfile($file);
            exit;
        } else {
            die("File not found.");
        }
    } else {
        die("File not found.");
    }
} else {
    die("Invalid request.");
}
?>
