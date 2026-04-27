<?php
include '../config/db.php';

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists
    $check = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0){
        $error = "Email already registered!";
    }
    else{

        // Default wallet balance = 10000
        mysqli_query($conn,
            "INSERT INTO users (name, email, password, balance)
             VALUES ('$name', '$email', '$password', 10000)"
        );

        $success = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width:400px;">
        <h4 class="mb-3 text-center">Register New User</h4>

        <?php if(isset($error)){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="register" class="btn btn-success w-100">Register</button>
        </form>

        <div class="mt-3 text-center">
            <a href="login.php">Already have account? Login</a>
        </div>
    </div>
</div>

</body>
</html>
