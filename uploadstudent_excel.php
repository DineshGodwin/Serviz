<?php
session_start();
ob_start();
include('db.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['import'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        $insertedCount = 0;
        $duplicateCount = 0;
        $duplicateregno = array();

        foreach ($data as $row) {
            if ($count > 0) {
                $regno = $row['0'];
                $name = $row['1'];
                $email = $row['2'];
                $class = $row['3'];
                // Extract the first two digits of the registration number to get the year
                $year = substr($regno, 0, 2);

                // Check if the email already exists in the table
                $checkQuery = "SELECT COUNT(*) as count FROM student WHERE regno = '$regno'";
                $checkResult = mysqli_query($conn, $checkQuery);
                $checkData = mysqli_fetch_assoc($checkResult);
                $emailExists = $checkData['count'] > 0;

                if (!$emailExists) {
                    // Insert the record if email does not exist
                    $studentQuery = "INSERT INTO student (regno,name,email,class,year) VALUES ('$regno','$name','$email','$class','$year')";
                    $result = mysqli_query($conn, $studentQuery);
                    $insertedCount++;
                } else {
                    // Record already exists, increment duplicate count and store email
                    $duplicateCount++;
                    $duplicateregno[] = $regno;
                }
            } else {
                $count++;
            }
        }

        if ($insertedCount > 0) {
            $_SESSION['message'] = "Successfully Imported $insertedCount records.";
            if ($duplicateCount == 1) {
                $_SESSION['message'] .= " $duplicateCount record is skipped due to duplicate records.";
                // Trigger JavaScript alert for duplicate records
                echo "<script>alert('Duplicate records found for regno's: " . implode(", ", $duplicateregno) . "');</script>";
            }
            else if ($duplicateCount > 1) {
                $_SESSION['message'] .= " $duplicateCount records are skipped due to duplicate records.";
                // Trigger JavaScript alert for duplicate records
                echo "<script>alert('Duplicate records found for emails: " . implode(", ", $duplicateregno) . "');</script>";
            }
        } else {
            $_SESSION['message'] = "No new records imported. All records might be duplicate.";
        }

        header('Location: managestudents.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Please select a file to import";
        header('Location: managestudents.php');
        exit(0);
    }
}
?>
