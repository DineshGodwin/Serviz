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
        $duplicateEmails = array();

        foreach ($data as $row) {
            if ($count > 0) {
                $name = $row['0'];
                $email = $row['1'];
                $pwd = password_hash($row['2'], PASSWORD_DEFAULT);
                $class = $row['3'];

                // Check if the email already exists in the table
                $checkQuery = "SELECT COUNT(*) as count FROM faculty WHERE email = '$email'";
                $checkResult = mysqli_query($conn, $checkQuery);
                $checkData = mysqli_fetch_assoc($checkResult);
                $emailExists = $checkData['count'] > 0;

                if (!$emailExists) {
                    // Insert the record if email does not exist
                    $studentQuery = "INSERT INTO faculty (name,email,password,classassg,verify_token) VALUES ('$name','$email','$pwd','$class',NULL)";
                    $result = mysqli_query($conn, $studentQuery);
                    $insertedCount++;
                } else {
                    // Record already exists, increment duplicate count and store email
                    $duplicateCount++;
                    $duplicateEmails[] = $email;
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
                echo "<script>alert('Duplicate records found for emails: " . implode(", ", $duplicateEmails) . "');</script>";
            }
            else if ($duplicateCount > 1) {
                $_SESSION['message'] .= " $duplicateCount records are skipped due to duplicate records.";
                // Trigger JavaScript alert for duplicate records
                echo "<script>alert('Duplicate records found for emails: " . implode(", ", $duplicateEmails) . "');</script>";
            }
        } else {
            $_SESSION['message'] = "No new records imported. All records might be duplicate.";
        }

        header('Location: managefaculty.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Please select a file to import";
        header('Location: managefaculty.php');
        exit(0);
    }
}
?>
