<?php
session_start();
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculty</title>
    <style>
body{
    height:1000px;
}

        table {
            width: 100%;
            height:600px;
        }
        .faculty-container {
    overflow-y: auto;
    max-height: 600px; /* Adjust the height as needed */
    width:100%;/* Add margin to separate from other elements */
}
        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            color: #324897;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
        tr:not(:first-child) {
            
            border: 1px solid #324897;
            border-radius: 20px;
        }
        
        #toolbar {
            margin-bottom: 50px;
        }

        #toolbar button {
            margin-top: 3px;
            margin-right: 10px;
            padding: 5px 10px;
            border: 1px solid #324897;
            border-radius: 5px;
            background-color: white;
            color: #324897;
            cursor: pointer;
            float:right;
        }

        #toolbar button:hover {
            background-color: #324897;
            color: white;
        }
        .im{
            margin-top:7px;
            padding: 5px 10px;
            border: 1px solid #324897;
            border-radius: 5px;
            background-color: white;
            color: #324897;
            cursor: pointer;
        }
        .imp{
       width:209px;
        }
        .im:hover{
            background-color: #324897;
            color: white;
        }
        #searchbar{
            float:right;
            margin-top:23px;
        }

        #faculty-table tr.highlight {
        background-color: yellow; /* You can customize the highlighting color */
        }
@media only screen and (min-width: 340px) and (max-width: 385px) {
    h2{
    margin-left:120px;
  }
  #toolbar{
    margin-left:50px;
  }
  
}

@media only screen and (min-width: 300px){
    h2{
    margin-left:15px;
  }
  #toolbar{
    margin-left:17px;
  }
  
}



@media(min-height: 300px){
        .faculty-container {
        max-height: calc(100vh - 200px);/* Adjust as needed */
    margin-bottom:1000px;
            
        }
}

@media (max-width: 485px) {
    #toolbar{
    display:flex;
    justify-content:center;
    margin-bottom:30px;
    }
    #faculty-table{
        margin-top:5px;
    }
}
    </style>
</head>
<body>

<div class="container">
    
    <h2 style="text-align:center;margin-top:80px;">Manage Faculty</h2>
    <div id="toolbar" class="container-fluid" style="width=100%">
        <button name="selectAll" onclick="selectAll()">Select All</button>
        <button onclick="deleteSelected()">Delete</button>
        <a href="addfaculty.php"><button>Add Faculty</button></a>
    </div>
    <div class="t1" style="float:left;margin-top:10px;">
        <form action="uploadfaculty_excel.php" method="POST" enctype="multipart/form-data">

<input type="file" name="import_file" class="imp">
<button type="submit" name="import" class="im">Import</button>
</form>
</div>
<input id="searchbar" type="text" placeholder="Search for names..">
<?php
if(isset($_SESSION['message'])) {
    // Store the session message in a JavaScript variable
    echo "<script>var sessionMessage = '".$_SESSION['message']."';</script>";
    // Unset the session message
    unset($_SESSION['message']);
}
?>
      <div class="faculty-container">          
    <table id="faculty-table" style="margin-top:30px;">

    </table>
            </div>
</div>
<div></div>
<?php include "footer.php"; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function deleteRecord(id) {
    var deleteConfirmed = confirm("Are you sure you want to delete this record?");
    if (deleteConfirmed) {
        // Proceed with deletion
        window.location.href = "deletefaculty.php?id=" + id;
    } else {
        event.preventDefault();
    }
}

    function deleteSelected() {
        var checkboxes = document.getElementsByName("deleteCheckbox");
        var selectedIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                selectedIds.push(checkboxes[i].value);
            }
        }

        if (selectedIds.length > 0) {
            var deleteConfirmed = confirm("Are you sure you want to delete the selected records?");
            if (deleteConfirmed) {
                // Proceed with deletion
                window.location.href = "deletefaculty.php?ids=" + selectedIds.join(",");
            }
        } else {
            alert("Please select at least one record to delete.");
        }
    }

    function selectAll() {
        var checkboxes = document.getElementsByName("deleteCheckbox");
        var selectAllButton = document.querySelector('button[name="selectAll"]');
        var allChecked = true;

        for (var i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) {
                allChecked = false;
                break;
            }
        }

        // Toggle the checkboxes
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = !allChecked;
        }

        // Update the button text
        selectAllButton.textContent = allChecked ? "Select All" : "Unselect All";
    }
    function uploadExcel(files) {
    var file = files[0];
    var formData = new FormData();
    formData.append('import_file', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'uploadfaculty_excel.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Reload the table after successful upload
            fetchFacultyData();
            alert('Excel file uploaded successfully.');
        } else {
            alert('Error uploading Excel file.');
        }
    };
    xhr.send(formData);
}
$(document).ready(function () {
$("#searchbar").on("keyup", function () {
    var value = $(this).val().toLowerCase().trim(); // Convert to lowercase and trim whitespace

    // Check if the search bar is empty
    if (value === "") {
        $("#faculty-table tr").removeClass("highlight");
        return; // Exit the function if the search bar is empty
    }

    // Remove highlight class from all rows
    $("#faculty-table tr").removeClass("highlight");

    // Apply highlighting for the matching cells
    $("#faculty-table td:nth-child(2), #faculty-table td:nth-child(3), #faculty-table td:nth-child(4)").filter(function () {
        var cellText = $(this).text().toLowerCase().trim(); // Convert to lowercase and trim whitespace
        var shouldHighlight = cellText.indexOf(value) > -1;
        if (shouldHighlight) {
            // Highlight the entire row if a match is found in any cell
            $(this).closest("tr").addClass("highlight");
        }
    });
});

});
// Function to fetch faculty data using AJAX
function fetchFacultyData() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the table content with the received data
                document.getElementById("faculty-table").innerHTML = xhr.responseText;
            }
        };
        xhr.open("GET", "fetch_faculty.php", true);
        xhr.send();
    }

    // Call fetchFacultyData() when the page loads to initially load the table
    window.onload = fetchFacultyData;
    
// This code will execute when the DOM is fully loaded
$(document).ready(function () {
    // Check if the sessionMessage variable is set
    if (typeof sessionMessage !== 'undefined') {
        // Display the session message as an alert
        alert(sessionMessage);
    }
});

    
</script>
</body>
</html>