<?php
include 'db.php';
// Retrieve the selected class from the query string
$selectedClass = $_GET["class"] ?? "";

// Fetch student and team details based on the selected class from the database
$sql = "SELECT s.name AS student_name, s.regno AS student_reg,s.email, t.gid, t.teamleader, t.tlreg
        FROM student s
        JOIN team t ON s.slt = t.gid AND t.class = ?
        ORDER BY t.gid"; // Order by group ID

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selectedClass); // Binding the parameter to avoid SQL injection
$stmt->execute();

$result = $stmt->get_result();

// Use PhpSpreadsheet to generate an Excel file
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$headers = ["Group ID", "Name", "Reg no","Email"];
$sheet->fromArray($headers, null, 'A1');

// Set data
$rowIndex = 2; // Start from the second row for data
$prevGroupId = null; // Variable to track the previous group ID

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if the group ID has changed
        if ($row['gid'] != $prevGroupId) {
            // Add data for the new group ID
            $sheet->setCellValue('A' . $rowIndex, $row['gid']);
            $prevGroupId = $row['gid'];
        }

        // Add data for each member
        $sheet->setCellValue('B' . $rowIndex, $row['student_name']);
        $sheet->setCellValue('C' . $rowIndex, $row['student_reg']);
        $sheet->setCellValue('D' . $rowIndex, $row['email']);
        $rowIndex++;
    }
} else {
    $sheet->setCellValue('A2', 'No records found for the selected class.');
}

// Set the appropriate headers to prompt the user to download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="students_teams.xlsx"');
header('Cache-Control: max-age=0');

// Save the spreadsheet as a file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
