<?php
ob_start(); 
include "aheader.php";
include "db.php";

// Start or resume the session
session_start();

// Initialize variables to store form data
$name = $regno = $email = $password = "";
$errorMsg = "";
$gid = $_GET['gid'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    $regno = isset($_POST["regno"]) ? $_POST["regno"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Validate form data
    if (empty($name) || empty($regno) || empty($email) || empty($password)) {
        $errorMsg = "All fields are required.";
    } else {
        // Check if the gid exists in the team table
        $checkGidSql = "SELECT gid FROM team WHERE gid = '$gid'";
        $result = $conn->query($checkGidSql);

        if ($result->num_rows > 0) {
            // Perform SQL insertion
            $insertStudentSql = "INSERT INTO student (name, regno, email, password, slt) VALUES ('$name', '$regno', '$email', '$password', '$gid')";

            if ($conn->query($insertStudentSql) === TRUE) {
                // Student inserted successfully
                $_SESSION['student_added'] = "Student added successfully.";

                // Update team table's count column
                $updateTeamSql = "UPDATE team SET no = (SELECT COUNT(*) FROM student WHERE slt = '$gid') WHERE gid = '$gid'";
                if ($conn->query($updateTeamSql) === TRUE) {
                    // Count column updated successfully
                    // JavaScript alert
                    echo '<script>alert("Student added successfully."); window.history.go(-2);</script>';
                    exit();
                } else {
                    $errorMsg = "Error updating team count: " . $conn->error;
                }
            } else {
                $errorMsg = "Error adding student: " . $conn->error;
            }
        } else {
            $errorMsg = "Invalid gid";
        }
    }
}

$conn->close();
ob_end_flush();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<style>

form {
    border: 1px solid #324897;
    border-radius: 31px;
    width: 400px;
    height: 450px;
    margin: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top:120px;
}


input, select {
    width: 80%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 30px;
    border: 1px solid #324897;
    justify-content: center;
}

button {
    width: 80%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 30px;
    border: 1px solid #324897;
    color: white;
    background-color: #324897;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex-grow: 1;
}

footer {
    background-color: #324897;
    color: white;
    text-align: center;
    padding: 10px;
    margin-top: 30px;
}
.view3{
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
            margin-top:80px;
            /* Add margin to separate from the table */
            width: 90px;
        }

</style>
</head>
<body>
    <div class="container mb-5">
    <button class="view3" onclick="goBack()">Go Back</button>   
    <form method="POST" class="mb-1">
    <h4 class="mt-3">Student Registration</h4>
    
    <input type="text" placeholder="Name" name='name' class="name mt-3" value="<?php echo htmlspecialchars($name); ?>">
    <input type="text" placeholder="Register Number" name='regno' class="regno mt-4" value="<?php echo htmlspecialchars($regno); ?>">
   
    <input type="email" placeholder="Email" name='email' class="email mt-4" value="<?php echo htmlspecialchars($email); ?>">
    <input type="password" placeholder="Password" name='password' class="password mt-4" value="<?php echo htmlspecialchars($password); ?>">

    <input type="hidden" name="gid" value="<?php echo htmlspecialchars($_GET['gid']); ?>">


    <?php
    // Display error message if there is any
    if (!empty($errorMsg)) {
        echo '<div class="result mt-1" style="color:red;">' . $errorMsg . '</div>';
    }
    ?>

    <button type="submit" class="mt-4">Submit</button>
</form>

    </div>
    <?php include 'footer.php'; ?>
    <script>
        function goBack(){
    window.history.back();
        }
    </script>
</body>
</html>
