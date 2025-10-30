<?php
include "includes/config.php";
ob_start();
session_start();

if (isset($_POST["submit"])) {
    $admin_user = $_POST["username"];
    $admin_pass = md5($_POST["password"]);  


    $check_query = "SELECT * FROM admin WHERE admin_USER = '$admin_user'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {

        echo "<script>alert('Username already exists. Please choose a different one.');</script>";
    } else {
 
        $admin_id = uniqid(); 
        $admin_id = substr($admin_id, -2); 


        $query = "INSERT INTO admin (admin_ID, admin_USER, admin_PASS) VALUES ('$admin_id', '$admin_user', '$admin_pass')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('New admin user added successfully!'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Error adding admin user: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Admin User</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Add New Admin User</h2>
    <form method="POST" action="admin_input.php">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" name="submit" class="btn btn-primary">Add Admin</button>
            </div>
        </div>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush();
?>