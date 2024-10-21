
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .card {
            border: none;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .card-header {
            background-color: #1d9a6c;
            color: #fff;
            border-radius: 10px 10px 0 0;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .form-control {
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #1d9a6c;
            border-color: #1d9a6c;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
            padding: 10px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #148c5a;
            border-color: #148c5a;
        }
    </style>
</head>
<body>

    <div class="card shadow">
        <div class="card-header">
            <h5 style="color:black;">Change password</h5>
        </div>
        <div class="card-body">
            <?php
                if(isset($_SESSION['status'])){
                    ?>
                    <div class="alert alert-success">
                        <h5><?=$_SESSION['status']; ?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']); 
                }
            ?>
            <form action="password_reset_code.php" method="POST">
                <input type="hidden" name='password_token' value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>">
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="email" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" class="form-control" placeholder="Enter your email address" required>
                </div>
                <div class="form-group mb-3">
                    <label for="">New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="form-group mb-3">
                    <label for="">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <button type="submit" name='password_update' class="btn btn-primary">Update password</button>
                </div>
            </form>
        </div>
    </div>
    <?php include('footer.php') ?>
</body>
</html>