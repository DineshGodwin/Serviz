<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Side Menu</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    #sideMenu {
      height: 100%;
      width: 0; /* Adjusted initial width */
      position: fixed;
      left: 0;
      top: 0;
      background-color: #324897;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 75px;
    }

    #sideMenu a {
      padding: 25px 8px 8px 32px;
      text-decoration: none;
      font-size: 18px;
      color: white;
      display: block;
      transition: 0.3s;
      text-align: center;
    }

    #sideMenu a:hover {
      background-color: #4a68a5; /* Hover color for side menu */
    }

    #menuToggle {
      font-size: 30px;
      cursor: pointer;
      position: fixed;
      z-index: 1;
      left: 20px;
      top: 20px;
      color: black; /* Color of hamburger icon */
    }

    #arrowIcon {
      font-size: 20px; /* Adjust the font size as needed */
      cursor: pointer;
      position: fixed;
      z-index: 1;
      left: 20px;
      top: 15px;
      display: none; /* Initially hide the arrow icon */
    }

    .user {
      background-color: #FFFFFF;
      border-radius: 3px;
      padding: 8px;
      color: #324797;
      text-align: center;
      font-size: 18px;
    }

    #logout {
      position: absolute;
      width: 100%;
      text-align: center;
      justify-content: center;
      color: white;
      bottom: 100px;
    }

    #logout a:hover {
      color: red; /* Hover color for logout button */
    }

    /* Media queries for responsive design */
    @media screen and (max-width: 576px) {
      #sideMenu {
        width: 0; /* Adjust the initial width for smaller screens */
      }
    }
  </style>
</head>
<body>

<?php

// Get the regno from the session
$regno = $_SESSION['regno'];

// Database connection details
include 'db.php';


// Check if regno is present in the team table
$sql = "SELECT * FROM team WHERE tlreg = '$regno'";
$result = $conn->query($sql);
$showSubmissionsAndRequests = $result->num_rows > 0;

// Close the database connection
$conn->close();
?>

<div id="menuToggle" onclick="toggleMenu()">&#9776; <!-- Hamburger Icon --></div>
<div id="arrowIcon" onclick="toggleMenu()">&#8592; <!-- Left Arrow Icon --></div>

<div id="sideMenu">
<div class="user"><i class="far fa-user" style="color: #324797;"></i> Hi, <?php echo $regno; ?></div>
  <a href="student_dash.php"> Dashboard </a>
  <a href="users.php"> Users </a>
  <?php if ($showSubmissionsAndRequests): ?>
    <a href="fileupload.php"> Submissions </a>
    <a href="requests.php"> Requests </a>
  <?php endif; ?>
  <a href="markcard.php"> Marks Card </a>
  <a href="sviewproject.php"> View Project </a>

  <!-- Add more links as needed -->

  <div id="logout">
    <a href="studentlogout.php">Logout<i class="fa-solid fa-arrow-right-from-bracket" style="color: #f3f2f2; padding-left:5px;"></i></a>
  </div>
</div>
<script>
function toggleMenu() {
  var sideMenu = document.getElementById('sideMenu');
  var menuToggle = document.getElementById('menuToggle');
  var arrowIcon = document.getElementById('arrowIcon');

  // Check if the 'small' class is present
  if (sideMenu.style.width === '0px') {
    // If 'small' is present, remove it
    sideMenu.style.width = '250px';
    menuToggle.style.display = 'none';
    arrowIcon.style.display = 'block';
  } else {
    // If 'small' is not present, add it
    sideMenu.style.width = '0';
    menuToggle.style.display = 'block';
    arrowIcon.style.display = 'none';
  }
}
</script>

</body>
</html>
