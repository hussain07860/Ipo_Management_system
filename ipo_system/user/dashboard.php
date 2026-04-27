<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id='$user_id'"
));
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Welcome <?php echo $user['name']; ?></span>
</nav>

<div class="container mt-5">

    <div class="card bg-primary text-white p-4">
        <h4>Wallet Balance</h4>
        <h2>₹ <?php echo $user['balance']; ?></h2>
    </div>

</div>

</body>
</html>
