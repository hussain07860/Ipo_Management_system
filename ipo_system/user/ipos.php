<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$ipos = mysqli_query($conn, "SELECT * FROM ipo");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available IPOs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Available IPOs</span>
    <a href="dashboard.php" class="btn btn-light btn-sm">Back</a>
</nav>

<div class="container mt-5">

    <div class="row">
        <?php while($ipo = mysqli_fetch_assoc($ipos)){ ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow p-3">
                    <h5><?php echo $ipo['company_name']; ?></h5>
                    <p>Price per Share: ₹ <?php echo $ipo['price_per_share']; ?></p>
                    <p>Available Shares: <?php echo $ipo['available_shares']; ?></p>

                    <form method="POST" action="apply.php">
                        <input type="hidden" name="ipo_id" value="<?php echo $ipo['ipo_id']; ?>">
                        <input type="number" name="shares" class="form-control mb-2" placeholder="Enter shares" required>
                        <button class="btn btn-success w-100">Apply IPO</button>
                    </form>

                </div>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
