<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['regno'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'studentlogin.php';
            }
          </script>";
    // Stop execution
    exit();
}
// Assuming you have a database connection, adjust it as needed
include 'db.php';


// Check if the user is logged in
if (!isset($_SESSION['regno'])) {
    // Redirect to login page or handle authentication as needed
    header("Location: studentlogin.php");
    exit();
}

// Assuming 'users' table has a 'user_id' column
$user_id = $_SESSION['regno'];

// Fetch distinct requests for the user's team (replace 'team' and 'gid' with your actual table and column names)
$query = "SELECT DISTINCT student.name,requests_table.gid,requests_table.request_id, requests_table.user_id, MAX(requests_table.timestamp) AS timestamp
          FROM requests_table
          JOIN team ON requests_table.gid = team.gid
          JOIN student ON requests_table.user_id = student.regno
          WHERE student.slt IS NULL";

$result = $conn->query($query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            text-align: center;
        }

        header, footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
        }

        h2 {
            color: #324897; 
        }
        
        table {
            
            margin-top:100px !important;
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #324897;
            text-align: left;
        }

        th, td {
            padding: 10px;
            margin:2px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            width: 60%;
        }

        .action-buttons button {
            background-color: #324897;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .accept-button {
            background-color: #4CAF50;
        }

        .decline-button {
            background-color: #FF5733;
        }

        @media screen and (max-width: 768px) {
            table {
                width: 100%;
            }
            .header,
            .footer {
                      background-color: #333;
                      color: #fff;
                      padding: 10px;
                      text-align: center;
                  }
        }
    </style>
</head>
<body>

    <?php //include 'header.php'; ?>
    
    <h2>Requests</h2>

    <?php
    $NullRows = 0;
while ($row1 = $result->fetch_assoc()) {
    if ($row1['timestamp'] === NULL) {
        $NullRows = 1;
        $row1="";
        break;
    }
}
//echo($NullRows);
$result = $conn->query($query);
    if ($NullRows!== 1 && $result && $result->num_rows > 0) {
       // echo("hellloooo");
       
    echo "<table>
            <tr>
                <th>Name</th>
                <th>User</th>
                <!-- Add more columns as needed -->
                <th>Created At</th>
                <th>Action</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        //var_dump($row);
        
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['user_id']}</td>
                <!-- Display more information about the request -->
                <td>{$row['timestamp']}</td>
                <td class='action-buttons'>
                    <button class='accept-button' onclick=\"acceptRequest('{$row['name']}', '{$row['user_id']}', '{$row['gid']}')\">Accept</button>
                    <button class='decline-button' onclick=\"declineRequest('{$row['name']}', '{$row['request_id']}', this)\">Decline</button>
                </td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No requests found.</p>";
}

    ?>
    

    <?php include 'footer.php'; ?>

    <!-- Include the student_sidebar.php file here -->
    <?php include 'student_sidebar.php'; ?>

    <script>
   function acceptRequest(name, userId, gid) {
 
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           
            console.log(this.responseText);
         
            updateButtonAppearance('accept-button');
        }
    };

    xhttp.open("POST", "acceptrequest.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhttp.send("name=" + encodeURIComponent(name) + "&userId=" + encodeURIComponent(userId) + "&gid=" + encodeURIComponent(gid));

    return false; 
}


function updateButtonAppearance(buttonClass) {
    var acceptButton = document.querySelector('.' + buttonClass);
    if (acceptButton) {
       
        acceptButton.innerHTML = '&#10004; Accepted';
      
    }
}

function declineRequest(name, requestId, buttonElement) {
        fetch('declinerequest.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'requestId=' + requestId,
        })
        .then(response => response.text())
        .then(data => {

            buttonElement.innerText = 'Declined';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

</script>


</body>
</html>

