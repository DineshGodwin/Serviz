<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// Check if user is not logged in, redirect to login page
/*if (!isset($_SESSION['name'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'facultylogin.php';
            }
          </script>";
    // Stop execution
    exit();
}*/
// Function to extract and export marks to Excel
function extractMarksToExcel($selectedClass)
{
    // Database connection
    include "db.php";

    // SQL query to fetch data from markstable by joining grouptable and student table
    $sql = "SELECT s.name AS Name, m.*
    FROM markstable m
    INNER JOIN team g ON m.gid = g.gid
    INNER JOIN student s ON m.regno = s.regno
    WHERE g.class = '$selectedClass'
    ORDER BY m.Gid";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers including 'Name' and excluding 'Mid'
        $headers = ['Name', 'Gid', 'Regno', 'Identification', 'Survey', 'Proof', 'Presentation', 'Evaluation', 'Deployement', 'Engagement', 'ESE'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
        }

        // Fetch data and populate Excel rows
        $rowIndex = 2;
        while ($row = $result->fetch_assoc()) {
            $columnIndex = 0; // Reset column index for each row
            foreach ($row as $key => $value) {
                // Skip 'Mid' column
                if ($key !== 'Mid') {
                    // Convert column index to alphabet (A, B, C, ...)
                    $columnLetter = chr(65 + $columnIndex);
                    // Set cell value in the correct column and row
                    $sheet->setCellValue($columnLetter . $rowIndex, $value);
                    $columnIndex++; // Increment column index for the next iteration
                }
            }
            $rowIndex++;
        }

        // Save Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'marks_' . $selectedClass . '.xlsx';
        $writer->save($filename);

        // Close database connection
        $conn->close();

        // Return the filename
        return $filename;
    } else {
        // Close database connection
        $conn->close();
        
        return false; // No results found
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $selectedClass = $_GET['selected-class'];

    // Call function to extract marks and get the filename
    $filename = extractMarksToExcel($selectedClass);

    if ($filename) {
        // Include header
        include('adminheader.php');

        // Provide the download link
        echo "<div style='margin-top: 20px; text-align: center;'>
        <div style='border: 2px solid blue; padding: 10px; display: inline-block; font-size: 20px;'>
            Marks extracted successfully! <a style='color: blue; text-decoration: none;' href='$filename'>Download Excel file</a>
        </div>
    </div>";

        // Include footer
        include('footer.php');
    } else {
        echo "<p>No data found for the selected class.</p>";
    }
}
?>
