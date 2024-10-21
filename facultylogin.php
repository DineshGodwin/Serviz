<?php ob_start(); 
session_start();
include 'db.php';
include 'header.php';
// Check if user is already logged in, redirect to dashboard if yes
if(isset($_SESSION['name'])) {
    header("Location: teacher.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        form{
            margin-top: 11px;  
            align-items: center;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: offwhite;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-message {
            color: red;
            margin-top: 5px;
            font-size:12px;
        }

        h4 {
            color: #324897;
            text-align: center;
            align-items: center;
        }

        a {
            font-size: 13px;
            text-align: center;
            display: block;
            margin-top: 20px;

        }

        .login-container {
            background-color: white; /* Semi-transparent blue */ /* Light blue with opacity */
    padding: 20px;
    height: 500px;
    width: 80%;
    max-width: 400px;
    border-radius: 20px; /* Adjust border-radius to make the border curved */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}



        .form-group {
            margin-bottom: 20px;
            align-items: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #324897;
            font-size: small;
        }
        input {
    width: 100%;
    padding: 8px;
    border: none; /* Remove the border */
    border-radius: 20px; /* Adjust border-radius to make the border curved */
    box-sizing: border-box;
    background-color: #f2f2f2; /* Optional: Add a background color */
}
img {
            width: 240px;
            height: 100px;
            margin-top: 10px;
            margin-left:35px;
        }

        .b1 {
            padding: 10px;
            cursor: pointer;
            background-color: #324897;
            color: #fff;
            border-radius: 4px;
            border: none;
            width: 30%;
            margin-bottom: 20px;
            margin-top: 30px;
            margin-left: 50px;
        }

        .submit{
            padding: 10px;
            cursor: pointer;
            background-color: #324897;
            color: #fff;
            border-radius: 4px;
            border: none;
            width: 30%;
            text-align: center;
            margin-left: 125px;
        }
        @media screen and (max-width: 600px) {
            .login-container {
                width: 90%;
            }

            .submit {
                width: 40%;
                margin-left: 30px;
            }
        }
        
        @media screen and (min-width: 300px) and (max-width:601px){
            .submit{
                margin-left:90px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
<img src="images/logo.jpg" alt="" class="img-fluid">

    <h4>Faculty Login</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            
            <input type="text" id="name" name="name" placeholder="Name" required >
        </div>
        <div class="form-group">
            
            <input type="text" id="email" name="email" placeholder="Email" required>
            <div id="email-error" class="error-message"></div> <!-- Placeholder for error message -->
        
        </div>
        <div class="form-group">
            
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        
            <button type="submit" class="submit">Submit</button>

        
        <a href="passresetpage.php">Forgot password? Click here</a>
        <a href="facultysignup.php">Don't have an account? Sign up now!</a>
        
    </form>
</div>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $password = $_POST["password"];
    $email = $_POST["email"];



    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
        echo "<script>document.getElementById('email-error').innerHTML = '$error_message';</script>";
        exit();
    }

    // Extract domain part of the email
    $domain = substr($email, strrpos($email, '@'));

    // Check if domain is "@christuniversity.in"
   /* if ($domain !== "@christuniversity.in") {
        $error_message = "Email must end with @christuniversity.in";
        echo "<script>document.getElementById('email-error').innerHTML = '$error_message';</script>";
        exit();
    }*/

    // Hashing the password for security
   

    $sql = "SELECT * FROM faculty WHERE name = '$name' AND email ='$email'";
    
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify hashed password
        $stored_password = $row['password'];
        //echo($stored_password);
        if (password_verify($password, $row['password'])) {
           
            $_SESSION['email'] = $row['email'];
            // Not sure what 'slt' is, assuming it's a field in your database
            $_SESSION['id'] = $row['id'];
            echo ($_SESSION['id'] );
            $_SESSION['name']=$name;
            if ($row['id'] === NULL) {
                echo("heloo sirrrrr");
            } else {
                header("Location: teacher.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid Password.');</script>";
            header("refresh:0.1;url=facultylogin.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid Name or Email.');</script>";
        header("refresh:0.1;url=facultylogin.php");
        exit();
    }
}

mysqli_close($conn);
include 'footer.php';
?>


</body>
</html>
