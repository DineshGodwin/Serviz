<?php
session_start();
include 'db.php';

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
$gid = $_SESSION['regno'];

// Fetch marks from the markstable
$fetchMarksSql = "SELECT identification, survey, proof, presentation, evaluation, deployement, engagement, ese FROM markstable WHERE regno = '$gid'";
$marksResult = $conn->query($fetchMarksSql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Card</title>
    <style>
        .header,
        .footer {
            background-color: #333;
            color: #fff;
            padding: 0px;
            text-align: center;
        }

        @media screen and (max-width: 768px) {
            .header,
            .footer {
                background-color: #333;
                color: #fff;
                text-align: center;
            }

            table {
                width: 100%;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 12px;
            }
        }

        table {
            margin-top:150px !important;
            border-collapse: collapse;
            margin: 20px auto;
            width: 70%;
        }

        th, td {
            border: 1px solid #333;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #324897;
            color: #fff;
            
        }

    </style>
</head>

<body>
    <div class="header">
        <?php include 'header.php'; ?>
    </div>

    <?php include 'student_sidebar.php'; ?>

    <div class="main-content">
        <?php
        // Check if there are marks to display
        if ($marksResult->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Criteria</th><th>Mark Awarded</th></tr>';
            
            // Output data of each column
            while ($row = $marksResult->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    if ($value !== null) {
                        echo '<tr>';
                        echo '<td style="";>' . $key . '</td>';
                        echo '<td>' . $value . '</td>';
                        echo '</tr>';
                    }
                }
            }
            
            echo '</table>';
        } else {
            echo '<p style="margin-top:200px;color:black;text-align:center;">No marks available.</p>';
        }
        ?>
    </div>

    <div class="footer">
        <?php include 'footer.php'; ?>
    </div>

</body>

</html>
