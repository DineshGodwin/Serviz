<?php
session_start();
include "header.php";
include "student_sidebar.php";

include "db.php";

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
}
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fetch distinct years for the filter dropdown
$sql_years = "SELECT DISTINCT year FROM idea";
$result_years = $conn->query($sql_years);
$years = array();
if ($result_years->num_rows > 0) {
    while ($row = $result_years->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Check if any search criteria is set
if (isset($_GET["searchByTitle"])) {
    $projectTitle = sanitize_input($_GET["projectTitle"]);
    // Perform database query with projectTitle filter
    $sql = "SELECT projtitle, projdesc, community, year FROM idea WHERE projtitle = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $projectTitle);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_GET["searchByCommunity"])) {
    $community = sanitize_input($_GET["community"]);
    // Perform database query with community filter
    $sql = "SELECT projtitle, projdesc, community, year FROM idea WHERE community = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $community);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No specific search criteria, display all records
    $sql = "SELECT projtitle, projdesc, community, year FROM idea";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <style>
        /* Add your CSS styles here */
        body{
            margin-top:100px !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #324897;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <?php if (isset($_GET["searchByTitle"])) { ?>
        <h2>Project Details for Title: <?php echo $projectTitle; ?></h2>
    <?php } elseif (isset($_GET["searchByCommunity"])) { ?>
        <h2>Project Details for Community: <?php echo $community; ?></h2>
    <?php } ?>
    <form id="filterForm">
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <option value="">All</option>
            <?php foreach ($years as $year) { ?>
                <option value="<?php echo $year; ?>" <?php if(isset($_GET['year']) && $_GET['year'] == $year) echo 'selected="selected"'; ?>><?php echo $year; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Filter">
    </form>
    <table id="projectTable">
        <tr>
            <th>Project Title</th>
            <th>Project Description</th>
            <th>Community</th>
            <th>Year</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0) { 
            $row_color = array('#ffffff', '#f9f9f9'); // Define row colors
            $i = 0; // Counter for row colors
            while ($row = $result->fetch_assoc()) {
                $color = $row_color[$i % 2]; // Alternate row colors
        ?>
        <tr class="projectRow" data-year="<?php echo $row['year']; ?>" style="background-color: <?php echo $color; ?>">
            <td><?php echo $row["projtitle"]; ?></td>
            <td><?php echo $row["projdesc"]; ?></td>
            <td><?php echo $row["community"]; ?></td>
            <td><?php echo $row["year"]; ?></td>
            <td>
                <a href="download.php?projtitle=<?php echo urlencode($row["projtitle"]); ?>">View</a>
            </td>
        </tr>
        <?php
            $i++; // Increment row color counter
            }
        } else { ?>
        <tr>
            <td colspan="5">No records found.</td>
        </tr>
        <?php } ?>
    </table>
    <script>
        // JavaScript to handle form submission via AJAX
        document.getElementById("filterForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var year = document.getElementById("year").value;
            var projectRows = document.getElementsByClassName("projectRow");
            for (var i = 0; i < projectRows.length; i++) {
                var rowYear = projectRows[i].getAttribute("data-year");
                if (year === "" || year === rowYear) {
                    projectRows[i].style.display = "";
                } else {
                    projectRows[i].style.display = "none";
                }
            }
        });
    </script>
    <?php
    include "footer.php";
    ?>
</body>
</html>