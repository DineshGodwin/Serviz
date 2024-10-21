<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header, footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
        }

        form {
            border: 2px solid #324897;
            padding: 30px;
            width: 80%; /* Adjust width as needed */
            max-width: 400px; /* Set maximum width */
            margin: 100px auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        h2 {
            color: #324897; /* Form heading color */
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

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            form {
                width: 90%;
            }
        }
    </style>
</head>
<body>
<?php include 'aheader.php'; ?>

<form action="aRegistration_process.php" method="post">
    <h2>Group Registration</h2>
    <label for="teamLeaderName">Team Leader Name:</label>
    <input type="text" id="teamLeaderName" name="teamLeaderName" required>

    <label for="registerNumber">Register Number:</label>
    <input type="text" id="registerNumber" name="registerNumber" required>

    <label for="selectSection">Select Section:</label>
    <select id="selectSection" name="section" required>
        <option value="CSE A">CSE A</option>
        <option value="CSE B">CSE B</option>
        <option value="CSE C">CSE C</option>
        <option value="AIML">AIML</option>
        <option value="DS">DS</option>
        <option value="IT">IT</option>
        <option value="IOT">IOT</option>
    </select>

    <label for="selectMembers">Select Number of Members:</label>
    <select id="selectMembers" name="num" onchange="generateMemberFields()" required>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>

    <div id="memberFieldsContainer"></div>

    <button type="submit">Submit</button>
</form>

<?php include 'footer.php'; ?>

<script>
    var memberRegisterNumbers = [];
    function generateMemberFields() {
        var selectMembers = document.getElementById('selectMembers');
        var memberFieldsContainer = document.getElementById('memberFieldsContainer');
        var numMembers = parseInt(selectMembers.value);

        // Clear previous fields
        memberFieldsContainer.innerHTML = '';

        if (numMembers > 0) {
            for (var i = 1; i <= numMembers; i++) {
                var nameLabel = document.createElement('label');
                nameLabel.textContent = 'Member ' + i + ' Name:';
                var nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = 'memberName' + i;
                nameInput.required = true;

                var regLabel = document.createElement('label');
                regLabel.textContent = 'Member ' + i + ' Register Number:';
                var regInput = document.createElement('input');
                regInput.type = 'text';
                regInput.name = 'memberRegisterNumber' + i;
                regInput.required = true;

                memberFieldsContainer.appendChild(nameLabel);
                memberFieldsContainer.appendChild(nameInput);
                memberFieldsContainer.appendChild(regLabel);
                memberFieldsContainer.appendChild(regInput);

                memberRegisterNumbers.push(regInput);
            }
        }
    }


    var jsonData = JSON.stringify(memberRegisterNumbers);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
  
            console.log(this.responseText);
        }
    };

    xhttp.open("POST", "aRegistration_process.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("jsonData=" + encodeURIComponent(jsonData));


</script>

</body>
</html>