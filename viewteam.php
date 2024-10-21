<?php
session_start();

include "aheader.php"; // Include any necessary header files
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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["gid"]) && isset($_GET["teamleader"])) {
    $groupId = $_GET["gid"];
    $teamLeader = $_GET["teamleader"];
    

    // Fetch team leader details based on the selected group ID and team leader
    $teamLeaderSql = "
    SELECT st.class, st.gid, st.teamleader, st.tlreg, rs.email
    FROM team st
    JOIN student rs ON st.tlreg = rs.regno
    WHERE st.gid = '$groupId' AND st.teamleader = '$teamLeader'";

    $teamLeaderResult = $conn->query($teamLeaderSql);

    // Fetch member details based on the selected group ID and team leader
    $memberSql = "
    SELECT rs.regno, rs.name, rs.email, rs.slt, st.class
    FROM student rs
    JOIN team st ON st.gid = rs.slt
    WHERE st.gid = '$groupId' AND rs.name != '$teamLeader'";

    $memberResult = $conn->query($memberSql);

    if ($teamLeaderResult === false || $memberResult === false) {
        echo "Error executing query: " . $conn->error;
        // You might want to handle the error in a better way, e.g., log it
    }

    // Extract team leader details
    $teamLeaderDetails = ($teamLeaderResult !== false && $teamLeaderResult->num_rows > 0) ? $teamLeaderResult->fetch_assoc() : null;

    // Extract member details
    $memberDetails = ($memberResult !== false && $memberResult->num_rows > 0) ? $memberResult->fetch_all(MYSQLI_ASSOC) : null;

    // Display success message or reload the page without error if the team leader was deleted successfully
    if(isset($_SESSION['team_leader_deleted'])) {
        echo $_SESSION['team_leader_deleted'];
        unset($_SESSION['team_leader_deleted']);
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
    <title>Team Details</title>
    <style>
        /* Add styles for the table */
        #team-member-table {
            width: 100%;
            margin-top: 20px;
        }

        #team-member-table th, #team-member-table td {
            padding: 10px;
            text-align: left;
            color: #324897;
        }

        #team-member-table tr:hover {
            background-color: #f5f5f5;
        }

        .view {
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
            
            /* Add margin to separate from the table */
            width: 90px;
        }

        #toolbar1 {
            margin-bottom: 20px;
            clear: both;
            /* Add this line to clear the floated elements */
        }

        #toolbar1 .add, .selectall, .deleteall {
            margin-top: 10px;
            margin-left: 5px;
            padding: 5px 10px;
            border: 1px solid #324897;
            border-radius: 5px;
            background-color: white;
            color: #324897;
            cursor: pointer;
            float: right;
        }

        #toolbar1 button:hover {
            background-color: #324897;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
<button class="view" onclick="goBack()">Go Back</button>
<h2 style="text-align:center;margin-top:120px;">Team Member Details</h2>
    <div id="toolbar1">
    <button class="add" onclick="addstudent('<?php echo $groupId; ?>')">Add Member</button>

        <button class="selectall" onclick="toggleSelectAll()">Select All</button>
        <button class="deleteall" onclick="deleteSelected()">Delete</button>

    </div>
    <?php
    if ($teamLeaderDetails !== null || $memberDetails !== null) {
        // Display team member details
        echo '<table id="team-member-table">';
        echo '<tr>';
        echo '<th></th>';
        echo '<th>Class</th>';
        echo '<th>Group ID</th>';
        echo '<th>Name</th>';
        echo '<th>Registration Number</th>';
        echo '<th>Email</th>';
        echo '<th>Action</th>';
        echo '</tr>';

        if ($teamLeaderDetails !== null) {
            echo '<tr>';
            echo '<td><input type="checkbox" name="deleteCheckbox" value="' . $teamLeaderDetails['tlreg'] . '"></td>';
            echo '<td>' . $teamLeaderDetails["class"] . '</td>';
            echo '<td>' . $teamLeaderDetails["gid"] . '</td>';
            echo '<td>' . $teamLeaderDetails["teamleader"] . '</td>';
            echo '<td>' . $teamLeaderDetails["tlreg"] . '</td>';
            echo '<td>' . $teamLeaderDetails["email"] . '</td>';
            echo '<td>';
            echo '<span style="color: #324897; cursor: pointer;" onclick="editRecord(' . $teamLeaderDetails['tlreg'] . ')"><i class="fa-solid fa-user-pen"></i></span> | ';
            echo '<span class="delete" onclick="deleteRecord(' . $teamLeaderDetails['tlreg'] . ')"><i class="fa-solid fa-trash" style="color: #324697;"></i></span>';
            echo '</td>';
            echo '</tr>';
        }

        // Display member details
        if ($memberDetails !== null) {
            foreach ($memberDetails as $member) {
                echo '<tr>';
                echo '<td><input type="checkbox" name="deleteCheckbox" value="' . $member['regno'] . '"></td>';
                echo '<td>' . $member["class"] . '</td>';
                echo '<td>' . $member["slt"] . '</td>';
                echo '<td>' . $member["name"] . '</td>';
                echo '<td>' . $member["regno"] . '</td>';
                echo '<td>' . $member["email"] . '</td>';
                echo '<td>';
                echo '<span style="color: #324897; cursor: pointer;" onclick="editRecord(' . $member['regno'] . ')"><i class="fa-solid fa-user-pen"></i></span> | ';
                echo '<span class="delete" onclick="deleteRecord(' . $member['regno'] . ')"><i class="fa-solid fa-trash" style="color: #324697;"></i></span>';
                echo '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    } else {
        echo "No records found for the team.";
    }
    ?>
</div>

<?php include "footer.php"; ?>


<script>
    function goBack() {
        window.location.href = "manageteams.php";
        
    }

    function toggleSelectAll() {
        var checkboxes = document.querySelectorAll('input[name="deleteCheckbox"]');
        var selectAllButton = document.querySelector('.selectall');
        var isChecked = selectAllButton.innerText === 'Select All';

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = isChecked;
        }

        selectAllButton.innerText = isChecked ? 'Unselect All' : 'Select All';
    }

    function deleteSelected() {
    var checkboxes = document.querySelectorAll('input[name="deleteCheckbox"]:checked');
    var regNos = [];
    checkboxes.forEach(function (checkbox) {
        regNos.push(checkbox.value);
    });

    if (regNos.length === 0) {
        alert("Please select at least one record to delete.");
        return;
    }

    if (!confirm("Are you sure you want to delete the selected records?")) {
        return;
    }

    // Send AJAX request to delete selected records
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "deletecheck.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Handle success response
                var response = xhr.responseText;
                if (response.startsWith("Error")) {
                    console.error("Error deleting records:", response);
                    alert(response); // Display error message
                } else {
                    console.log("Records deleted successfully");
                    window.location.reload(); // Refresh the page after deletion
                }
            } else {
                // Handle error response
                console.error("Error deleting records:", xhr.statusText);
                alert("Error deleting records. Please try again."); // Display generic error message
            }
        }
    };

    // Construct data to be sent with the request
    var data = "regnos=" + encodeURIComponent(JSON.stringify(regNos));
    data += "&groupId=<?php echo $groupId; ?>"; // Include groupId

    xhr.send(data);
}


function deleteRecord(regNo) {
    if (confirm("Are you sure you want to delete this record?")) {
        // Send AJAX request to delete the record
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "deletestudent.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Handle success response
                    var response = xhr.responseText;
                    if (response.startsWith("Error")) {
                        console.error("Error deleting record:", response);
                        alert(response); // Display error message
                    } else {
                        console.log("Record deleted successfully");
                        window.location.reload(); // Refresh the page after deletion
                    }
                } else {
                    // Handle error response
                    console.error("Error deleting record:", xhr.statusText);
                    alert("Error deleting record. Please try again."); // Display generic error message
                }
            }
        };

        // Construct data to be sent with the request
        var data = "regno=" + encodeURIComponent(regNo);
        data += "&gid=<?php echo $groupId; ?>"; // Include groupId

        xhr.send(data);
    }
}


function editRecord(regNo) {
    // Redirect to edit page with registration number as parameter
    window.location.href = "editstudent.php?regno=" + regNo;
}

function addstudent(gid) {
    // Check if the count of team members is less than 5
    var rowCount = document.querySelectorAll('#team-member-table tr').length - 1; // Subtract 1 for the header row
    if (rowCount < 5) {
        // Redirect to add student page
        window.location.href = "addstudent.php?gid=" + gid;
    } else {
        // Disable the button
        var addButton = document.querySelector('.add');
        addButton.disabled = true;
        alert("Maximum team size reached. You cannot add more students.");
    }
}


</script>

</body>
</html>