<?php
session_start();

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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header, footer {
            background-color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
        }

        form {
            margin-top:100px !important;
            border: 1px solid #324897;
            border-radius: 10px;
            padding: 20px;
            width: 80%;
            max-width: 550px;
            margin: 20px auto 0;
            margin-bottom: 20px;
        }

        .select {
            width: 100%;
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
            display: block;
            margin: 0 auto;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #324897;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons button {
            background-color: #324897;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px;
        }

        #myModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            
        }

        @media screen and (max-width: 768px) {
            form {
                width: 90%;
                max-width: 100%;
            }

            table {
                width: 100%;
            }

            .modal-content {
                padding: 20px;
            }

            .action-buttons button {
                margin-right: 5px;
            }
        }

        @media screen and (max-width: 480px) {
            form {
                width: 95%;
            }

            .modal-content {
                padding: 15px;
            }

            .action-buttons button {
                margin-right: 5px;
            }
        }
    </style>
</head>
<body>

    <?php include 'header.php';
    include 'student_sidebar.php';?>

    <form action="" method="post" onsubmit="return fetchGroupInfo()">
        <label for="groupSelect">Select Group:</label>
        <select id="groupSelect" name="selectSection" class="select">
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

    <div id="groupInfo"></div>

    <!-- Modal for displaying team details -->
    <div id="myModal" class="modal">
        <div class="modal-content" id="modalContent">
            <!-- Team details will be displayed here -->
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function fetchGroupInfo() {
            var selectedSection = document.getElementById('groupSelect').value;

            // You can use AJAX to fetch data from displaygroup.php and update the table
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('groupInfo').innerHTML = this.responseText;
                }
            };

            xhttp.open("POST", "displaygroup.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("selectSection=" + selectedSection);

            return false; // Prevent the form from submitting traditionally
        }
        
      


        function sendRequest(groupId, button) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
            // Handle the response as needed
                    console.log(this.responseText);

            // Change the button to a tick mark
                    button.innerHTML = "&#10003;"; // Unicode for tick mark
                    button.disabled = true; // Disable the button to prevent further clicks
                    localStorage.setItem(groupId, true);
                     alert("Request sent successfully!");
                }
            };

            xhttp.open("POST", "sendrequest.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("groupId=" + groupId);

            return false; // Prevent the form from submitting traditionally
        }



        function viewGroup(groupId) {
            var modal = document.getElementById('myModal');
            var modalContent = document.getElementById('modalContent');

            // You can use AJAX to fetch team details from the server based on the groupId
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    modalContent.innerHTML = this.responseText;
                    modal.style.display = 'flex';
                }
            };

            xhttp.open("POST", "getteamdetails.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("groupId=" + groupId);
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
    </script>

</body>
</html>
