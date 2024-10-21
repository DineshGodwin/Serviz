<?php
ob_start(); 
include "aheader.php";
include "db.php";

// Check if either 'id' or 'ids' parameter is present in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Single record deletion
    $id = $_GET['id'];
    $deleteSql = "DELETE FROM faculty WHERE id = $id";

    if ($conn->query($deleteSql) === TRUE) {
        // Redirect to managefaculty.php after successful deletion
        header("Location: managefaculty.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} elseif (isset($_GET['ids']) && !empty($_GET['ids'])) {
    // Multiple record deletion
    $ids = explode(",", $_GET['ids']);
    $ids = array_map('intval', $ids);
    $idsString = implode(",", $ids);
    $deleteSql = "DELETE FROM faculty WHERE id IN ($idsString)";

    if ($conn->query($deleteSql) === TRUE) {
        // Redirect to managefaculty.php after successful deletion
        header("Location: managefaculty.php");
        exit();
    } else {
        echo "Error deleting records: " . $conn->error;
    }
} else {
    echo "Invalid or missing 'id' or 'ids' parameter in the URL.";
}

// Display pop-up message after successful deletion
echo "<script>alert('Record/Records deleted successfully.');</script>";

$conn->close();
ob_end_flush();
?>
