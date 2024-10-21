<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviz</title>
    <style>
        img {
            width: 300px;
            height: 100px;
            margin-top: 10px;
            margin-left:5px;
        }

    button {
    background-color: #324897;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 34px !important;
    cursor: pointer;
    box-sizing: border-box;
    max-width: 400px;
    width: 100%; /* Full width for buttons */
}
#button-container {
    margin-top:200px;
    margin-left:550px !important;
    width:30%;
    border: 1px solid #dddddd; /* Border style */
    border-radius: 20px; /* Border radius for rounded corners */
   padding:40px;/* Padding inside the box */
    padding-left:20px !important;
    padding-right:20px !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Box shadow */
    display: flex; /* Set display to flex */
    flex-direction: column; /* Align buttons in a single vertical line */
    align-items: center; /* Center-align buttons horizontally */
    gap: 20px; /* Add gap between buttons */
}

</style>
</head>
<body>
    <?php include 'header.php';?>
    <div id="button-container">
    <img src="images/logo.jpg" alt="" class="img-fluid">    
    <a href="studentlogin.php"><button >Student login</button></a>
    <a href="facultylogin.php"><button>Faculty Login</button></a>
    <?php include 'footer.php';?>
</body>
</html>