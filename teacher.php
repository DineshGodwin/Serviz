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
}
?>
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
            border-radius: 56px !important;
            cursor: pointer;
            width: 150px;
            /* Adjusted width */
        }
        #exportButton {
            background-color: #324897;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            float: right; /* Align button to the right */
            margin-bottom: 15px; /* Fixed typo */
            margin-left: 10px; /* Add some space between the form and the button */
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
<?php include 'adminheader.php';?>
<?php include 'sidebar.php'; ?>
    
    

<div class="container">



    <form id="classForm">
        <label for="classSelect">Select Class:</label>
        <select id="classSelect" name="class" class="select">
            <option value="CSE A">CSE A</option>
            <option value="CSE B">CSE B</option>
            <option value="CSE C">CSE C</option>
            <option value="AIML">AIML</option>
            <option value="DS">DS</option>
            <option value="IT">IT</option>
            <option value="IOT">IOT</option>
        </select>
        <button type="submit">Submit</button>
    </form>

    <button id="exportButton">Export to Excel</button>
    <div id="classDataContainer"></div>
</div>

<script>
    // JavaScript code to submit the form asynchronously
    document.getElementById("classForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Get form data
        var xhr = new XMLHttpRequest(); // Create new XMLHttpRequest object

        xhr.open("POST", "get_class_data.php", true); // Configure the request

        // Define what happens on successful data submission
        xhr.onload = function() {
            if (xhr.status === 200) { // If request was successful
                document.getElementById("classDataContainer").innerHTML = xhr.responseText; // Update content of classDataContainer
            }
        };

        xhr.send(formData); // Send the form data
    });

    // Function to export content to Excel
    function exportContentToExcel(content) {
        var html = content; // Here you pass the content you want to export

        // Create a blob from the HTML content
        var blob = new Blob([html], { type: 'application/vnd.ms-excel' });

        // Create a temporary URL for the blob
        var url = URL.createObjectURL(blob);

        // Create a link element and set its attributes
        var a = document.createElement('a');
        a.href = url;
        a.download = 'exported_content.xls'; // File name
        document.body.appendChild(a);

        // Trigger the download
        a.click();

        // Clean up
        setTimeout(function () {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 0);
    }

    // Add event listener to the export button
    document.getElementById("exportButton").addEventListener("click", function() {
        // Get the selected class
        var selectedClass = document.getElementById("classSelect").value;

        // Send request to export_to_excel.php with selected class
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "generate_excel_data.php", true);
        xhr.responseType = "blob";

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Create a temporary URL for the blob
                var url = window.URL.createObjectURL(xhr.response);

                // Create a link element and set its attributes
                var a = document.createElement('a');
                a.href = url;
                a.download = 'exported_data.xls'; // File name
                document.body.appendChild(a);

                // Trigger the download
                a.click();

                // Clean up
                setTimeout(function () {
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }, 0);
            }
        };

        // Create FormData object and append selected class
        var formData = new FormData();
        formData.append('class', selectedClass);

        // Send the request
        xhr.send(formData);
    });
</script>
<?php include 'footer.php';?>
</body>
</html>
