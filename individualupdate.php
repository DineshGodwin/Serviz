<?php session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['name'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'facultylogin.php';
            }
          </script>";
    // Stop execution
    exit();
}?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Select Student Marks</title>
  <meta charset="UTF-8">
    <title>Select Student Marks</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h4{
            color:#324897;
            text-align: center;
            font-weight:bold;
            justify-content: center;
        }

        h2 {
            color: #324897;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            color:#324897;
        }

        th {
            background-color: #324897;
            color: white;
            
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        input[type="number"] {
            width: 120px;
        }

        input[type="submit"] {
            background-color: #324897;
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }


    </style>
</head>
<body>


<?php include('adminheader.php'); ?>
<form method="get" action="selectmarks.php" >
<button style="background-color: #324897; color: white;" type="submit" name="go_back">Go Back</button>
</form>

<?php
if(isset($_GET['gid'])) {
    $gid = $_GET['gid'];
    
    // Connect to the database
include 'db.php';

    // Query to get student information based on gid 
    $sql_students = "SELECT regno, name FROM student WHERE slt = '$gid'";
    $result_students = $conn->query($sql_students);
    
    if ($result_students->num_rows > 0) {
        // Display dropdown to select student

        
        echo "<form method='post' action='' style='text-align: center;'>";
        echo "<div style='display: inline-block;'>"; // Wrap form elements in a div
        echo "<select id='student-dropdown' name='student-dropdown' required>";
        echo "<option value='' style='border-radius: 5px;' disabled selected>Select Student</option>"; // Placeholder option
        echo "<option value='all'>All</option>"; // Add 'all' option
        while ($row = $result_students->fetch_assoc()) {
            $regno = $row["regno"];
            $name = $row["name"];
            echo "<option value='$regno'>$name ($regno)</option>";
        }
        echo "</select>";
        echo "<input type='hidden' name='gid' value='$gid'>";
        echo "<input type='submit' name='select_student' value='Select'  style='border-radius: 5px; width: 60px; height: 30px;'>";
        echo "</div>"; // Close the div
        echo "</form>";
        
        
    } else {
        echo "No students found for this group.";
    }
    
    if(isset($_POST['select_student'])) {
        $selected_regno = $_POST['student-dropdown'];
        if ($selected_regno === 'all') {
            // Query to fetch marks for all students in the group
            $sql_all_marks = "SELECT s.regno, s.name, mt.identification, mt.survey, mt.proof, mt.presentation, mt.evaluation, mt.deployement, mt.engagement, mt.ese
                              FROM student s
                              LEFT JOIN markstable mt ON s.regno = mt.regno AND mt.gid = '$gid'
                              WHERE s.slt = '$gid'";
            $result_all_marks = $conn->query($sql_all_marks);
    
            if ($result_all_marks->num_rows > 0) {
                echo "<h4>All Students Marks</h4>";
                echo "<form method='post' action=''>";
    
                echo "<table style='width: 1200px; border-collapse: collapse; border: 2px solid #dddddd; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);margin-left:150px;'>";
                echo "<tr><th>Student Name</th><th>Identification of problem in the community Inline with SDG topics ( 5marks)</th><th>2. Literature Survey (5 marks)</th><th>3. Proof of concept ( 5 Marks)</th><th>4. Ideathon/Poster Presentation/Paper presentation (10 marks)</th><th>5. Prototype evaluation (5 marks)</th><th>6. Deployment (10 marks)</th><th>7. Community Engagement (5 marks)</th><th>8. ESE Project Presentation (5 marks)</th></tr>";
                while ($row = $result_all_marks->fetch_assoc()) {
                    $student_regno = $row["regno"];
                    $student_name = $row["name"];
                    $identification_marks = $row["identification"];
                    $survey_marks = $row["survey"];
                    $proof_marks = $row["proof"];
                    $presentation_marks = $row["presentation"];
                    $evaluation_marks = $row["evaluation"];
                    $deployment_marks = $row["deployement"];
                    $engagement_marks = $row["engagement"];
                    $ese_marks = $row["ese"];
    
                    echo "<tr>";
                    echo "<td>$student_name ($student_regno)</td>";
                    echo "<td><input type='number' name='identification[$student_regno]' placeholder='" . ($identification_marks != '' ? $identification_marks : 'Enter marks') . "' value='$identification_marks' required></td>";
                    echo "<td><input type='number' name='survey[$student_regno]' placeholder='" . ($survey_marks != '' ? $survey_marks : 'Enter marks') . "' value='$survey_marks' required></td>";
                    echo "<td><input type='number' name='proof[$student_regno]' placeholder='" . ($proof_marks != '' ? $proof_marks : 'Enter marks') . "' value='$proof_marks' required></td>";
                    echo "<td><input type='number' name='presentation[$student_regno]' placeholder='" . ($presentation_marks != '' ? $presentation_marks : 'Enter marks') . "' value='$presentation_marks' required></td>";
                    echo "<td><input type='number' name='evaluation[$student_regno]' placeholder='" . ($evaluation_marks != '' ? $evaluation_marks : 'Enter marks') . "' value='$evaluation_marks' required></td>";
                    echo "<td><input type='number' name='deployment[$student_regno]' placeholder='" . ($deployment_marks != '' ? $deployment_marks : 'Enter marks') . "' value='$deployment_marks' required></td>";
                    echo "<td><input type='number' name='engagement[$student_regno]' placeholder='" . ($engagement_marks != '' ? $engagement_marks : 'Enter marks') . "' value='$engagement_marks' required></td>";
                    echo "<td><input type='number' name='ese[$student_regno]' placeholder='" . ($ese_marks != '' ? $ese_marks : 'Enter marks') . "' value='$ese_marks' required></td>";
                    echo "</tr>";
                }
                echo "</table>";
    
                echo "<input type='hidden' name='gid' value='$gid'>";
echo "<input type='submit' name='update_all_marks' value='Update All Marks' style='margin-bottom:100px;'>";
                echo "</form>";
            } else {
                echo "No students found for this group.";
            }
        } else {
            $sql_student = "SELECT s.regno, s.name, s.slt, g.teamleader
                        FROM student s
                        JOIN team g ON s.slt = g.gid
                        WHERE s.slt = '$gid' AND s.regno = '$selected_regno'" ;
        $result_student = $conn->query($sql_student);
        
        if ($result_student->num_rows > 0) {
            // Fetch student data
            $row_student = $result_student->fetch_assoc();
            $student_name = $row_student["name"];
            $student_regno = $row_student["regno"];
            $student_gid = $row_student["slt"];
            $team_leader_name = $row_student["teamleader"];
            
            // Display student information
            echo "<h4>Student Information</h4>";
            echo "<table style='width: 500px; border-collapse: collapse; border: 2px solid #dddddd; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);margin-left:500px;'>";

echo "<tr><td>Student Name:</td><td>$student_name</td></tr>";
echo "<tr><td>Registration Number:</td><td>$student_regno</td></tr>";
echo "<tr><td>Group ID:</td><td>$student_gid</td></tr>";
echo "<tr><td>Team Leader Name:</td><td>$team_leader_name</td></tr>";
echo "</table>";

            
            // Display form to update marks
            echo "<h4>Update Marks</h4>";
            
            echo "<form method='post' action=''>";
            

// Query to fetch marks for the selected student
$sql_marks = "SELECT * FROM markstable WHERE gid = '$gid' AND regno = '$selected_regno'";
$result_marks = $conn->query($sql_marks);

// Check if marks exist for the selected student
if ($result_marks->num_rows > 0) {
    // Fetch the marks from the result
    $row_marks = $result_marks->fetch_assoc();
    $identification_marks = $row_marks["identification"];
    $survey_marks = $row_marks["survey"];
    $proof_marks = $row_marks["proof"];
    $presentation_marks = $row_marks["presentation"];
    $evaluation_marks = $row_marks["evaluation"];
    $deployment_marks = $row_marks["deployement"];
    $engagement_marks = $row_marks["engagement"];
    $ese_marks = $row_marks["ese"];
} else {
    // If no marks found, initialize variables with empty values
    $identification_marks = "";
    $survey_marks = "";
    $proof_marks = "";
    $presentation_marks = "";
    $evaluation_marks = "";
    $deployment_marks = "";
    $engagement_marks = "";
    $ese_marks = "";
}

// Display form to update marks with default values for input fields
echo "<form method='post' action=''>";

echo "<table style='width: 1000px; border-collapse: collapse; border: 2px solid #dddddd; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);margin-left:250px;'>";

echo "<tr><th style='text-align: center;'>Criteria</th><th style='text-align: center;'>Marks</th></tr>";
echo "<tr><td>1. Identification of problem in the community Inline with SDG topics ( 5marks)</td><td><input type='number' name='identification' placeholder='Enter marks' value='$identification_marks'required></td></tr>";
echo "<tr><td>2. Literature Survey (5 marks)</td><td><input type='number' name='survey' placeholder='Enter marks' value='$survey_marks' required></td></tr>";
echo "<tr><td>3. Proof of concept (  5 Marks)</td><td><input type='number' name='proof' placeholder='Enter marks' value='$proof_marks' required></td></tr>";
echo "<tr><td>4. Ideathon/Poster Presentation/Paper presentation (10 marks)</td><td><input type='number' name='presentation' placeholder='Enter marks' value='$presentation_marks' required></td></tr>";
echo "<tr><td>5. Prototype evaluation (5 marks)</td><td><input type='number' name='evaluation' placeholder='Enter marks' value='$evaluation_marks' required></td></tr>";
echo "<tr><td>6. Deployment  (10 marks)</td><td><input type='number' name='deployment' placeholder='Enter marks' value='$deployment_marks' required></td></tr>";
echo "<tr><td>7. Community Engagement (5 marks)</td><td><input type='number' name='engagement' placeholder='Enter marks' value='$engagement_marks' required></td></tr>";
echo "<tr><td>8. ESE Project Presentation (5 marks)</td><td><input type='number' name='ese' placeholder='Enter marks' value='$ese_marks' required></td></tr>";
echo "</table>";

echo "<input type='hidden' name='gid' value='$gid'>";
echo "<input type='hidden' name='regno' value='$selected_regno'>";
echo "<input type='submit' name='submit_marks' value='Submit' style='text-align: center;margin-left:1150px;margin-bottom: 100px;'>";
echo "</form>";

           
        } else {
            echo "No student found for this group and registration number.";
        }
        }
        // Query to get student information based on gid and regno

    }
    
    // Handle form submission
    if(isset($_POST['submit_marks'])) {
        $identification = $_POST['identification'];
        $survey = $_POST['survey'];
        $proof = $_POST['proof'];
        $presentation = $_POST['presentation'];
        $evaluation = $_POST['evaluation'];
        $deployment = $_POST['deployment'];
        $engagement = $_POST['engagement'];
        $ese = $_POST['ese'];
        
        // Get Gid and Regno from the form submission
        $gid = $_POST['gid'];
        $regno = $_POST['regno'];
        
        // Insert or update into marks table
        $sql_marks = "INSERT INTO markstable (gid, regno, identification, survey, proof, presentation, evaluation, deployement, engagement, ese)
                      VALUES ('$gid', '$regno', '$identification', '$survey', '$proof', '$presentation', '$evaluation', '$deployment', '$engagement', '$ese')
                      ON DUPLICATE KEY UPDATE
                      identification = '$identification', survey = '$survey', proof = '$proof', presentation = '$presentation',
                      evaluation = '$evaluation', deployement = '$deployment', engagement = '$engagement', ese = '$ese'";
        if ($conn->query($sql_marks) === TRUE) {
            echo "<script>alert('Marks updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating marks');</script> " . $conn->error;
        }
    }
    // Handle form submission for updating all marks
if(isset($_POST['update_all_marks'])) {
    // Get all submitted marks for each student
    $identification_marks = $_POST['identification'];
    $survey_marks = $_POST['survey'];
    $proof_marks = $_POST['proof'];
    $presentation_marks = $_POST['presentation'];
    $evaluation_marks = $_POST['evaluation'];
    $deployment_marks = $_POST['deployment'];
    $engagement_marks = $_POST['engagement'];
    $ese_marks = $_POST['ese'];

    // Iterate through each student's marks
    foreach ($identification_marks as $student_regno => $identification) {
        // Update marks in the database
        $sql_update_marks = "INSERT INTO markstable (gid, regno, identification, survey, proof, presentation, evaluation, deployement, engagement, ese)
                            VALUES ('$gid', '$student_regno', '$identification', '$survey_marks[$student_regno]', '$proof_marks[$student_regno]', 
                            '$presentation_marks[$student_regno]', '$evaluation_marks[$student_regno]', '$deployment_marks[$student_regno]', 
                            '$engagement_marks[$student_regno]', '$ese_marks[$student_regno]')
                            ON DUPLICATE KEY UPDATE
                            identification = '$identification', survey = '$survey_marks[$student_regno]', proof = '$proof_marks[$student_regno]', 
                            presentation = '$presentation_marks[$student_regno]', evaluation = '$evaluation_marks[$student_regno]', 
                            deployement = '$deployment_marks[$student_regno]', engagement = '$engagement_marks[$student_regno]', 
                            ese = '$ese_marks[$student_regno]'";
        

        
    }
    echo "<script>alert('";

    if ($conn->query($sql_update_marks) === TRUE) {
        echo "Marks updated successfully ";
    } else {
        echo "Error updating marks ";
    }
    
    echo "');</script>";
}

    $conn->close();
} else {
    echo "GID not provided.";
}
?>
    <?php
    include "footer.php";
    ?>
<!-- Button to redirect to selectmarks.php -->




</body>
</html>
