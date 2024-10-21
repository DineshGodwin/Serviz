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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            border: 1px solid #324897;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .select {
            width: calc(100% - 20px); /* Adjusted width */
            padding: 10px;
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
            border-radius: 4px;
            cursor: pointer;
            width: calc(100% - 20px); /* Adjusted width */
        }

        #classDataContainer {
            margin-top: 20px;
            overflow-x: auto; /* Added to handle horizontal scrolling if necessary */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #324897;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }

        /* Media query for smaller screens */
        @media screen and (max-width: 600px) {
            form, button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<?php error_reporting(E_ALL);
ini_set('display_errors', 1);?>

<?php include 'adminheader.php'; ?>
<?php include 'sidebar.php';

?>
<div class="container">
    <form id="SubmissionType">
    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

        <label for="SubmissionType">Submission Type:</label>
        <select id="SubmissionType" name="submission" class="select">
            <option value="Completion Letter">Completion Letter</option>
            <option value="Weekly Report">Weekly Report</option>
            <option value="Acceptance Letter">Acceptance Letter</option>
            <option value="Final Report">Final Report</option>
        </select>

        <button type="submit">Select</button>
    </form>
    <div id="classDataContainer"></div>
</div>

<?php include 'footer.php'; ?>

<script>
    // JavaScript code to submit the form asynchronously
    document.getElementById("SubmissionType").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this); // Get form data
    formData.append('id', <?php echo json_encode($_GET['id']); ?>); // Append additional parameter
    
    var xhr = new XMLHttpRequest(); // Create new XMLHttpRequest object

    xhr.open("POST", "get_files.php", true); // Configure the request

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (xhr.status === 200) { // If request was successful
            document.getElementById("classDataContainer").innerHTML = xhr.responseText; // Update content of classDataContainer
        }
    };

    xhr.send(formData); // Send the form data with appended parameter
});


</script>

</body>
</html>
