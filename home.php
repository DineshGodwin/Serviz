<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .header, .footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: center;
            margin: 200px; 
        }

        .button {
            margin: 10px; 
            text-decoration: none;
            display: inline-block;
        }

        .button img {
            
            width: 60%; 
            height: auto;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        @media screen and (max-width: 600px) {
            .button-container {
                margin: 10px; 
            }

            .button {
                margin: 5px; 
            }
        }
    </style>    
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="button-container">
        <a href="creategroup.php" class="button"><img src="creategroup.png" alt="Create Group"></a>
        <a href="viewgroup.php" class="button"><img src="viewgroup.png" alt="View Group"></a>
    </div>
    

    <?php include 'footer.php'; ?>

</body>
</html>
