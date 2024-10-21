<?php
// Start the session
session_start();

include "aheader.php"; // Include any necessary header files
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["regno"])) {
    $regNo = $_GET["regno"];

    // Fetch student details based on registration number
    $studentSql = "SELECT * FROM student WHERE regno = '$regNo'";
    $studentResult = $conn->query($studentSql);

    if ($studentResult === false) {
        echo "Error executing query: " . $conn->error;
        // You might want to handle the error in a better way, e.g., log it
    } else {
        $studentDetails = ($studentResult->num_rows > 0) ? $studentResult->fetch_assoc() : null;
        // Retrieve group ID from the fetched student details
        $gid = ($studentDetails !== null) ? $studentDetails['slt'] : null;
    }
} else {
    // Handle invalid request, redirect to an error page, or display an error message
    echo "Invalid request";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
            form {
            border: 1px solid #324897;
            border-radius: 31px;
            width: 390px;
            height: 420px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top:120px;
        }

        label {
            float: left;
            width: 80%; /* Set the width of the label */
            margin-top:3px;
        }

        input {
            width: 80%;
            padding: 7px;
            
            border-radius: 30px;
            border: 1px solid #324897;
            justify-content: center;
        }

        .up {
            width: 80%;
            padding: 10px;
            margin: 5px 0; /* Add margin to create space between the input and the button */
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
        .view1 {
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
 margin-top:110px;
        }
        @media only screen and (min-width: 411px) and (max-width: 455px) {
    .view1{
    margin-bottom:20px;
    color:red;
  }
form{
    width: 340px;
            height: 420px;
}
}
@media only screen and (min-width: 456px) and (max-width: 500px) {
    .view1{
    margin-bottom:20px;
    color:red;
  }
form{
    width: 340px;
     height: 420px;
}
}
    </style>
    <script>    function goBack() {
        window.history.back();
        
    }</script>
</head>
<body>
<div class="container">
<button class="view1" onclick="goBack()">Go Back</button>
    <?php
    if ($studentDetails !== null) {
        ?>
        <form method="post" action="updatestudent.php">
            <h3 class="mt-3 mb-4"style="text-align: center;">Edit Student Details</h3>
            <input type="hidden" name="regno" value="<?php echo $studentDetails['regno']; ?>">
            <input type="hidden" name="gid" value="<?php echo $gid; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $studentDetails['name']; ?>"><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $studentDetails['email']; ?>"><br><br>
            <button class="up"type="submit" value="Update">Update</button>
        </form>
    <?php 
    } else {
        echo "Student record not found.";
    }
    ?>
</div>
<?php include "footer.php"; ?>


</body>
</html>
