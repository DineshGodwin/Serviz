<?php
// Start session
session_start();

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

include "aheader.php";
include "db.php";


// Initialize $result as null
$result = null;

// Initialize $selectedClass
$selectedClass = isset($_POST["selectedClass"]) ? $_POST["selectedClass"] : null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectedClass"])) {
    // Fetch student details based on the selected class from the database
    $sql = "SELECT gid, teamleader, tlreg, class FROM team WHERE class = '$selectedClass' ";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        echo "Error executing query: " . $conn->error;
        // You might want to handle the error in a better way, e.g., log it
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <style>
        /* Your existing styles */
        <style>
        /* Your existing styles */

        /* Add styles for the form container */
        .form-container {
            margin-bottom: 20px;
        }



        .table-container {
    overflow-y: auto;
    max-height: 400px; /* Adjust the height as needed */
    width:100%;/* Add margin to separate from other elements */
}
        /* Add styles for the table */
        #student-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom:50px;
            display: none; /* Hide the table initially */
            
        }

        #student-table th, #student-table td {
            padding: 10px;
            text-align: left;
        }

        #student-table th {
            color: #324897;
            position: sticky;
            top: 0; /* Stick to the top of the table container */
            background-color: #fff; /* Optional: Add background color for better readability */
            z-index:0;
        }

        #student-table tr:hover {
            background-color: #f5f5f5;
        }

        .view-team-details-button {
            background-color: #4caf50;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin-top: 10px;
            cursor: pointer;
        }
        .sub {
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .ex{
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float:left;
            width:103px;
            display: none; /* Hide the extract button initially */
        }
        #student-table tr.highlight {
        background-color: yellow; /* You can customize the highlighting color */
        }
        #searchbar{
            float:right;
            display:none;
            margin-top:23px;
        }

        @media (max-width: 767px) {
    .table-container {
        max-height: calc(100vh - 200px); /* Adjust the maximum height based on the viewport height */
        /* You can adjust the subtraction value (200px) to fit your layout */
    }
}

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
$(document).ready(function () {
    // Function to update the table with teams
    function updateTeams(selectedClass) {
        // Hide the table initially
        $("#student-table").hide();
        $("#no-records-message").hide(); // Hide the message initially

        $.ajax({
            type: "GET",
            url: "getteams.php",
            data: { class: selectedClass },
            dataType: "json",
            success: function (response) {
                // Clear the existing table
                $("#student-table tbody").empty();

                if (response.error) {
                    // Display error message
                    console.log("Error message:", response.error);
                    $("#no-records-message").show(); // Show the message
                    // Hide the extract button and search bar
                    $(".ex").hide();
                    $("#searchbar").hide();
                } else if (response.teams && Object.keys(response.teams).length > 0) {
                    // Display the table with team details
                    console.log("Teams found:", response.teams);
                    var teams = response.teams;
                    $.each(teams, function (groupId, teamLeaders) {
                        $.each(teamLeaders, function (teamLeader, students) {
                            var row = "<tr>";
                            row += "<td><input type='checkbox' class='checkbox' data-regno='" + students[0]["tlreg"] + "' data-gid='" + groupId + "'></td>";
                            row += "<td>" + students[0]["class"] + "</td>";
                            row += "<td>" + groupId + "</td>";
                            row += "<td>" + teamLeader + "</td>";
                            row += "<td>" + students[0]["no"] + "</td>";
                            row += "<td><a href='viewteam.php?gid=" + encodeURIComponent(groupId) + "&teamleader=" + encodeURIComponent(teamLeader) + "'>View Team Details</a></td>";
                            row += "</tr>";

                            $("#student-table tbody").append(row);
                        });
                    });

                    // Show the table, extract button, and search bar
                    $("#student-table").show();
                    $(".ex").show();
                    $("#searchbar").show();
                } else {
                    // No records found
                    console.log("No records found for class:", selectedClass);
                    $("#no-records-message").show(); // Show the message
                    // Hide the extract button and search bar
                    $(".ex").hide();
                    $("#searchbar").hide();
                }

                // Reset the form
               // $("#class-form")[0].reset();
            },
            error: function (xhr, status, error) {
                console.error("AJAX error: " + error);
            }
        });
    }

    // Event listener for form submission
    $("#class-form").submit(function (e) {
        e.preventDefault();
        var selectedClass = $("#selectedClass").val();
        updateTeams(selectedClass);
    });

    // Event listener for search bar
    $("#searchbar").on("keyup", function () {
        var value = $(this).val().toLowerCase().trim(); // Convert to lowercase and trim whitespace

        // Check if the search bar is empty
        if (value === "") {
            $("#student-table tr").removeClass("highlight");
            return; // Exit the function if the search bar is empty
        }

        // Remove highlight class from all rows
        $("#student-table tr").removeClass("highlight");

        // Apply highlighting for the matching cells
        $("#student-table td:nth-child(2), #student-table td:nth-child(3), #student-table td:nth-child(4)").filter(function () {
            var cellText = $(this).text().toLowerCase().trim(); // Convert to lowercase and trim whitespace
            var shouldHighlight = cellText.indexOf(value) > -1;
            if (shouldHighlight) {
                // Highlight the entire row if a match is found in any cell
                $(this).closest("tr").addClass("highlight");
            }
        });
    });

    // Event listener for select all button
    $("#select-all").click(function () {
        var isChecked = $(this).prop("checked");
        $(".checkbox").prop("checked", isChecked);
        $(this).val(isChecked ? "Unselect All" : "Select All");
    });

    // Event listener for delete button
    $("#delete-selected").click(function () {
        var gids = [];
        $(".checkbox:checked").each(function () {
            gids.push($(this).data("gid"));
        });

        if (gids.length === 0) {
            alert("Please select at least one record to delete.");
            return;
        }

        if (!confirm("Are you sure you want to delete the selected records?")) {
            return;
        }

        // Send AJAX request to delete selected records
        $.ajax({
            type: "POST",
            url: "deleteteam.php",
            data: { gids: JSON.stringify(gids) }, // Pass the gids array as JSON string
            success: function (response) {
                alert(response);
                // Reload the page after deletion
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("AJAX error: " + error);
            }
        });
    });

    // Function to extract file
    function extractFile() {
        var selectedClass = $("#selectedClass").val();
        location.href = 'excel.php?class=' + encodeURIComponent(selectedClass);
    }

    // Event listener for extract button
    $(".ex").click(function () {
        extractFile();
    });
});

</script>
</script>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;margin-top:90px;">Manage Teams</h2>

    <div style="float:right;border:1px solid #324897;border-radius:15px;padding:6px;">
    <input type="checkbox" id="select-all"> <label for="select-all">Select All</label>
    <a id="delete-selected" title="Delete"><i class="fa-solid fa-trash" style="color: #324697;cursor:pointer;margin-left:10px;"></i></a>
    <a href="addteams.php" title="Add teams"><i class="fa-solid fa-user-plus" style="color: #324697;cursor:pointer;margin-left:10px;"></i></a>
        
    
    </div>
    <!-- Form to select semester and class -->
    <div class="form-container mt-3">
        <form method="POST" id="class-form">

            <label for="selectedClass" style="padding:5px;">Select Class:</label>
            
            <select name="selectedClass" id="selectedClass" style="padding:5px;margin-top:5px;">
                <!-- Add your class options dynamically from the database if needed -->
                <?php
                // Assume you have a list of classes like ["BTCS A", "BTCS B", ...]
                $classes = ["CSE A", "CSE B", "CSE C", "AIML", "DS", "IT", "IOT"];

                foreach ($classes as $class) {
                    echo '<option value="' . $class . '">' . $class . '</option>';
                }
                ?>
            </select>

            <button class="sub" type="submit">Submit</button>
            
        </form>
    </div>
    <input id="searchbar" type="text" placeholder="Search for names..">
    <button class="ex mt-3" type="button" onclick="extractFile()">Extract file</button>
    <div class="table-container">
    <table id="student-table">
        <thead>
            <tr>
                <th></th>
                <th>Class</th>
                <th>Group ID</th>
                <th>Team Leader</th>
                <th>No of members</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table body content will be populated dynamically using AJAX -->
        </tbody>
    </table>
            </div>
    <div id="no-records-message" style="display: none; text-align: center;">No records found for the selected class.</div>

</div>

<?php include "footer.php"; ?>

</body>
</html>
