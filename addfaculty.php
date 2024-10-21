<?php
session_start();
ob_start(); 
include "aheader.php";
include "db.php";

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'adminlogin.php';
            }
          </script>";
    // Stop execution
    exit();
}
// Initialize variables to store form data
$id = $username = $email = $pwd = $assg = "";
$errorMsg = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $pwd = isset($_POST["pwd"]) ? $_POST["pwd"] : "";
    $assg = isset($_POST["assg"]) ? $_POST["assg"] : "";

    // Validate form data
    if (empty($username) || empty($email) || empty($pwd) || empty($assg)) {
        $errorMsg = "All fields are required.";
    } else {
        // Perform SQL insertion
        $insertSql = "INSERT INTO faculty (name, email, password, classassg) VALUES ('$username', '$email', '$pwd', '$assg')";
        
        if ($conn->query($insertSql) === TRUE) {
            // Redirect to managefaculty.php after successful insertion
            header("Location: managefaculty.php");
            exit();
        } else {
            $errorMsg = "Error adding faculty: " . $conn->error;
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


input {
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
.view {
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
            margin-top:100px;
            /* Add margin to separate from the table */
            width: 90px;
        }
</style>
</head>
<body>
    <div class="container mb-5">
    <button class="view" onclick="goBack()">Go Back</button>
        <form method="POST" class="mb-1">
        <h4 class="mt-3">Faculty Registration</h4>
            
            <input type="text" placeholder="Username" name='username' class="username mt-3" value="<?php echo htmlspecialchars($username); ?>">
            <input type="email" placeholder="Email" name='email' class="email mt-4" value="<?php echo htmlspecialchars($email); ?>">
            <input type="password" placeholder="Password" name='pwd' class="pwd mt-4" value="<?php echo htmlspecialchars($pwd); ?>">
            <input type="text" placeholder="Class assigned" name='assg' class="assg mt-4" value="<?php echo htmlspecialchars($assg); ?>">

            <?php
            // Display error message if there is any
            if (!empty($errorMsg)) {
                echo '<div class="result mt-1" style="color:red;">' . $errorMsg . '</div>';
            }
            ?>

            <button type="submit" class="mt-4">Submit</button>
        </form>
        <p style="text-align: center; font-style: italic; color: #777; margin-top: 20px;">
            Note: Please assign classes like CSE A, CSE B, CSE C, IT, IOT, DS, AIML.
        </p>
    </div>
    <?php include 'footer.php'; ?>
    <script>    
    function goBack() {
        window.location.href = "managefaculty.php";
        
    }</script>
</body>
</html>
