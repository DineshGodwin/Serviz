<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
        }

        .Logo {
            font-family: 'Inria Serif';
            color: white;
            font-size:17px;
        }

        .Logo1 {
            font-family: 'Inria Serif';
            color: white;
            font-size:19px;
            margin-right:65px;
        }

        .header {
            background-color: #324897;
            color: white;
            text-align: center;
            padding: 5px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            
        }

        @media screen and (max-width: 576px) {
      .Logo1 {
        margin-right:25px;
         /* Adjust the initial width for smaller screens */
      }
      
    }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <?php include "adminsidebar.php"; ?>
    <section class="header">
        <div class="container d-sm-flex flex-column justify-content-end pt-1">
            <div class="Logo1 align-self-end line1">CHRIST</div> <!-- Wrap CHRIST in a div with class line1 -->
            <div class="Logo align-self-end line2">DEEMED TO BE UNIVERSITY</div> <!-- Wrap DEEMED TO BE UNIVERSITY in a div with class line2 -->
        </div>
    </section>
</body>
</html>