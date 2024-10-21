<?php
// Include necessary files and start session
session_start();
ob_start();
include "aheader.php";
include "db.php";

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    // Redirect to login page
    header("Location: adminlogin.php");
    exit(); // Stop execution
}

// Fetch distinct classes for the class dropdown
$sql_classes = "SELECT DISTINCT class FROM student";
$result_classes = $conn->query($sql_classes);
$classes = array();
if ($result_classes->num_rows > 0) {
    while ($row = $result_classes->fetch_assoc()) {
        $classes[] = $row['class'];
    }
}

// Fetch distinct years for the year dropdown
$sql_years = "SELECT DISTINCT year FROM student";
$result_years = $conn->query($sql_years);
$years = array();
if ($result_years->num_rows > 0) {
    while ($row = $result_years->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Default to showing all student details if class and year are not selected
if (!isset($_POST['class']) && !isset($_POST['year'])) {
    $show_table = false;
} else {
    // Check if both class and year are selected
    if (isset($_POST['class']) && isset($_POST['year'])) {
        $selected_class = $_POST['class'];
        $selected_year = $_POST['year'];

        // Prepare the SQL query to fetch student details based on class and year
        $sql = "SELECT regno, name, email, class FROM student WHERE class = ? AND year = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $selected_class, $selected_year);
        $stmt->execute();
        $result = $stmt->get_result();

        $show_table = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
</head>
<body>
    <h2 style="text-align: center;margin-top:100px;">Manage Students</h2>
    <form method="POST">
        <label for="class">Select Class:</label>
        <select name="class" id="class">
            <option value="">All</option>
            <?php foreach ($classes as $class) { ?>
                <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
            <?php } ?>
        </select>

        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <option value="">All</option>
            <?php foreach ($years as $year) { ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php } ?>
        </select>

        <button type="submit">Apply Filter</button>
    </form>
    <?php if ($show_table) { ?>
    <div class="t1" style="float:left;margin-top:10px;">
        <form action="uploadstudent_excel.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="import_file" class="imp">
            <button type="submit" name="import" class="im">Import</button>
        </form>
    </div>

    <table border="1">
        <tr>
            <th>Registration Number</th>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
        </tr>
        <?php if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['regno']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No records found.</td>
            </tr>
        <?php } ?>
    </table>
    <?php } ?>

<?php include "footer.php"; ?>
</body>
</html>
