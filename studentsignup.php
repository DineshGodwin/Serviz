<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup</title>
    
    
<style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header, footer {
            background-color: white;
            padding: 30px;
            text-align: center;
        }
        .error-message {
            color: red;
            margin-top: 5px;
            font-size:12px;
        }
        form {
            background-color: white;
            padding: 10px;
            border-radius: 32px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 80%;
            max-width: 350px;
            margin: auto;
            display: flex;
            flex-direction: column;
            margin-top: 30px;
            margin-bottom:500px:
        }

        fieldset {
            border: none;
            padding-top:30px !important;
            padding-right:30px !important;
            padding-left:30px !important;
            padding-bottom:0px !important;
            margin: 0;
        }

        legend {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #324897;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 32px !important;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            margin-top: 0px !important;
            margin-bottom:0px !important;
        }


        @media screen and (max-width: 600px) {
            form {
                width: 90%;
            }
        }
    </style>
    
</head>
<body>
    <header>
    </header>

    <form id="signup-form" method="POST" onsubmit="return validateForm()">
        <fieldset>
            <legend>Student Signup</legend>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email ID:</label>
            <input type="email" id="email" name="email" required>
            <div id="email-error" class="error-message"></div>

            <label for="regno">Register No:</label>
            <input type="text" id="regno" name="regno" required>
            <div id="regno-error" class="error-message"></div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <div id="confirm-password-error" class="error-message"></div>
            <button style="width:100%;" type="submit" id="submit-button">Submit</button>
        </fieldset>


    </form>

    <script>
        function validateForm() {
    var email = document.getElementById('email').value;
    var regno = document.getElementById('regno').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;

    // Check if password and confirm password match
    if (password !== confirmPassword) {
        document.getElementById('confirm-password-error').innerText = "Passwords do not match.";
        return false; // Prevent form submission
    } else {
        document.getElementById('confirm-password-error').innerText = ""; // Clear error message
    }

    // Check if email is ending with "@btech.christuniversity.in"
   /* if (!email.endsWith('@btech.christuniversity.in')) {
        document.getElementById('email-error').innerText = "Email must end with @btech.christuniversity.in";
        return false; // Prevent form submission
    } else {
        document.getElementById('email-error').innerText = ""; // Clear error message
    }*/

    // Check if email and regno are already present
    checkIfExists(email, regno);
    return false; // Prevent default form submission
}

        function checkIfExists(email, regno) {
            console.log(email);
            console.log(regno);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        console.log(response);
                        if (response.emailExists) {
                            document.getElementById('email-error').innerText = "Email already exists.";
                        } else {
                            document.getElementById('email-error').innerText = "";
                            document.getElementById("signup-form").action = "otp.php"; // Set action to otp.php
                            document.getElementById("signup-form").submit(); // Submit the form
                        }
                        if (response.regnoExists) {
                            document.getElementById('regno-error').innerText = "Registration number already exists.";
                        } else {
                            document.getElementById('regno-error').innerText = "";
                        }
                    } else {
                        console.log('Error: ' + xhr.status);
                    }
                }
            };
            xhr.open("POST", "checkIfExists.php", true);
            console.log("working");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("email=" + email + "&regno=" + regno);
        }
    </script>
</body>
</html>
<?php include 'footer.php';?>