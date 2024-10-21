<?php
session_start();
ob_start();
include 'db.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        form {
            border: 1px solid #324897;
            border-radius: 31px;
            width: 410px;
            height: 425px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            
        }

        img {
            width: 240px;
            height: 100px;
            margin-top: 20px;
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
    </style>
</head>
<body>
    
<div class="container mb-5"style="margin-top:80px ;">
        <form method="POST" class="mt-5 mb-5" id="loginForm">
            <img src="images/logo.jpg" alt="" class="img-fluid">
            <h4>Admin Login</h4>
            <input type="text" placeholder='Username' name='username' class="Username mt-4">
            <input type="password" placeholder="Password"  name='pwd' class="pwd mt-3">
            <a href="forgot.php" class="mt-3">Forgot Password?</a>
            <div id="errorMsg" class="result mt-3" style="color:red; display:none;"></div>
            <button type="submit" class="mt-3">Login</button>
        </form>
    </div>

    <script>
        // JavaScript code to display alert instead of error message
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            var username = document.getElementsByName('username')[0].value;
            var password = document.getElementsByName('pwd')[0].value;

            if (username === '' || password === '') {
                event.preventDefault(); // Prevent form submission
                document.getElementById('errorMsg').style.display = 'block';
                document.getElementById('errorMsg').innerText = 'Input fields must not be empty';
                setTimeout(function() {
                    document.getElementById('errorMsg').style.display = 'none';
                }, 3000); // Hide the alert after 3 seconds
            }
        });
    </script>
    <?php
    include 'footer.php';
    ?>
</body>
</html>
<?php

$errorMsg = "";

// Start session

// Check if user is already logged in, redirect to dashboard if yes
if(isset($_SESSION['username'])) {
    header("Location: managefaculty.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['pwd'])) {
        $username = $_POST['username'];
        $password = $_POST['pwd'];
        // Rest of the code remains unchanged

        // Validate if fields are not empty
        if (empty($username) || empty($password)) {
            $errorMsg = "Input fields must not be empty";
        } else {
            // Perform SQL query to check credentials
            $sql = "SELECT * FROM admin WHERE BINARY username='$username' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Set session variable
                $_SESSION['username'] = $username;
                // Redirect to dashboard if credentials are correct
                header("Location: managefaculty.php");
                exit();
            } else {
                $errorMsg = "Invalid username or password";
            }
        }
    }
}
?>