<?php
session_start();
$page_title = "Forgot password";
include "header.php";
?>

<div class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

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
            <div class="card">
                <div class="card-header">
                    <h5>Reset password</h5>
                </div>
                <div class="card-body">
                    <form action="adminpass_reset_code.php" method="POST">
                        <div class="form-group mb-3">
                            <label>Email Address</label>
                            <input type="email" name='email' class='form-control' placeholder="Enter your email address" required>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" name="password_reset_link" class="btn btn-primary">Send password reset link</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
