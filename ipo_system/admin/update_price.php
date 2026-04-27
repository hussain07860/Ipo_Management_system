<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/admin_login.php");
    exit();
}

$message = "";

// When admin submits new price
if(isset($_POST['update_price'])){

    $ipo_id = $_POST['ipo_id'];
    $new_price = $_POST['new_price'];

    mysqli_query($conn,
        "INSERT INTO price_history (ipo_id, price)
         VALUES ('$ipo_id', '$new_price')"
    );

    $message = "Price Updated Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update IPO Price</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9;">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Update IPO Price</span>
    <a href="dashboard.php" class="btn btn-light btn-sm">Back</a>
</nav>

<div class="container mt-5">

    <?php if($message != ""): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 shadow">

        <form method="POST">

            <label class="form-label">Select IPO</label>
            <select name="ipo_id" class="form-select mb-3" required>
                <?php
                $ipos = mysqli_query($conn, "SELECT ipo_id, company_name FROM ipo");
                while($ipo = mysqli_fetch_assoc($ipos)){
                    echo "<option value='{$ipo['ipo_id']}'>{$ipo['company_name']}</option>";
                }
                ?>
            </select>

            <label class="form-label">Enter New Price</label>
            <input type="number" step="0.01" name="new_price" class="form-control mb-3" required>

            <button type="submit" name="update_price" class="btn btn-primary w-100">
                Update Price
            </button>

        </form>

    </div>

</div>

</body>
</html>
