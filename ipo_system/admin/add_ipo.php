<?php
include '../config/db.php';

if(isset($_POST['add_ipo'])){

    $company = $_POST['company_name'];
    $price = $_POST['price_per_share'];
    $total = $_POST['total_shares'];
    $available = $_POST['available_shares'];
    $open = $_POST['open_date'];
    $close = $_POST['close_date'];

    mysqli_query($conn,
        "INSERT INTO ipo 
        (company_name, price_per_share, total_shares, available_shares, open_date, close_date, status)
        VALUES 
        ('$company','$price','$total','$available','$open','$close','OPEN')"
    );

    header("Location: add_ipo.php?success=1");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add IPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3>Add New IPO</h3>

        <?php if(isset($_GET['success'])){ ?>
            <div class="alert alert-success mt-3">
                ✅ IPO Added Successfully!
            </div>
        <?php } ?>

        <form method="POST" class="mt-3">

            <div class="mb-3">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Price Per Share</label>
                <input type="number" step="0.01" name="price_per_share" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Total Shares</label>
                <input type="number" name="total_shares" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Available Shares</label>
                <input type="number" name="available_shares" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Open Date</label>
                <input type="date" name="open_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Close Date</label>
                <input type="date" name="close_date" class="form-control" required>
            </div>

            <button type="submit" name="add_ipo" class="btn btn-primary">
                Add IPO
            </button>

            <a href="dashboard.php" class="btn btn-secondary">
                Back
            </a>

        </form>
    </div>
</div>

</body>
</html>
