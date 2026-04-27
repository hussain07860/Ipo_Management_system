<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Refresh user data
$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id='$user_id'"
));

// ================= REQUEST ADD MONEY =================
if(isset($_POST['add_money'])){
    $amount = (int) $_POST['amount'];

    if($amount <= 0){
        header("Location: home.php?error=invalid_amount");
        exit();
    }

    mysqli_query($conn,
        "INSERT INTO wallet_requests (user_id, amount)
         VALUES ('$user_id', '$amount')"
    );

    header("Location: home.php?success=request_sent");
    exit();
}


// ================= APPLY IPO =================
if(isset($_POST['apply_ipo'])){

    $ipo_id = $_POST['ipo_id'];
    $shares = (int) $_POST['shares'];

    $ipo = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM ipo WHERE ipo_id='$ipo_id'"
    ));

    $price = $ipo['price_per_share'];
    $total_cost = $price * $shares;

    if($shares <= 0){
        header("Location: home.php?error=invalid_shares");
        exit();
    }

    if($total_cost > $user['balance']){
        header("Location: home.php?error=low_balance");
        exit();
    }

    mysqli_query($conn,
        "UPDATE users SET balance = balance - '$total_cost'
         WHERE user_id='$user_id'"
    );

    mysqli_query($conn,
        "INSERT INTO ipo_application (user_id, ipo_id, shares_applied, status)
         VALUES ('$user_id', '$ipo_id', '$shares', 'PENDING')"
    );

    header("Location: home.php?success=applied");
    exit();
}

// Fetch data
$ipos = mysqli_query($conn, "SELECT * FROM ipo");
$applications = mysqli_query($conn,
    "SELECT ia.*, i.company_name
     FROM ipo_application ia
     JOIN ipo i ON ia.ipo_id = i.ipo_id
     WHERE ia.user_id='$user_id'"
);
$portfolio = mysqli_query($conn,
    "SELECT p.*, i.company_name
     FROM portfolio p
     JOIN ipo i ON p.ipo_id = i.ipo_id
     WHERE p.user_id='$user_id'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#f1f5f9;">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Welcome <?php echo $user['name']; ?></span>

    <div>
        <a href="price_history.php" class="btn btn-info btn-sm">Price History</a>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>


<div class="container-fluid">
<div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-2 bg-white shadow-sm p-4" style="min-height:100vh;">
        <h6 class="text-muted">Navigation</h6>
        <hr>
        <p><strong>Dashboard</strong></p>
        <p>Available IPOs</p>
        <p>My Applications</p>
        <p>My Portfolio</p>
    </div>

    <!-- MAIN CONTENT -->
    <div class="col-md-10 p-4">

        <!-- ALERTS -->
        <?php if(isset($_GET['error'])){ ?>
            <div class="alert alert-danger">
                <?php
                    if($_GET['error']=='invalid_shares') echo "Invalid share quantity.";
                    if($_GET['error']=='low_balance') echo "Insufficient wallet balance.";
                ?>
            </div>
        <?php } ?>

        <?php if(isset($_GET['success'])){ ?>
            <div class="alert alert-success">
                <?php
                   if($_GET['success']=='applied') echo "IPO Applied Successfully.";
if($_GET['success']=='request_sent') echo "Balance request sent to admin for approval.";

                ?>
            </div>
        <?php } ?>

        <!-- WALLET CARD -->
        <div class="card shadow mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Wallet Balance</h5>
                    <h2 class="text-primary">₹ <?php echo $user['balance']; ?></h2>
                </div>
                <form method="POST" class="d-flex">
                    <input type="number" name="amount" class="form-control me-2" placeholder="Add Money" required>
                    <button class="btn btn-success" name="add_money">Add</button>
                </form>
            </div>
        </div>

        <!-- IPO SECTION -->
        <h4 class="mb-3">Available IPOs</h4>
        <div class="row mb-5">
            <?php while($ipo = mysqli_fetch_assoc($ipos)){ ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5><?php echo $ipo['company_name']; ?></h5>
                            <p>Price: ₹ <?php echo $ipo['price_per_share']; ?></p>
                            <p>Available Shares: <?php echo $ipo['available_shares']; ?></p>

                            <form method="POST">
                                <input type="hidden" name="ipo_id" value="<?php echo $ipo['ipo_id']; ?>">
                                <input type="number" name="shares" class="form-control mb-2" placeholder="Enter Shares" required>
                                <button class="btn btn-primary w-100" name="apply_ipo">Apply IPO</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- APPLICATIONS -->
        <h4>My Applications</h4>
        <div class="card shadow mb-5">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Company</th>
                            <th>Shares Applied</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($app = mysqli_fetch_assoc($applications)){ ?>
                            <tr>
                                <td><?php echo $app['company_name']; ?></td>
                                <td><?php echo $app['shares_applied']; ?></td>
                                <td>
                                    <?php if($app['status']=='APPROVED'){ ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PORTFOLIO -->
        <h4>My Portfolio</h4>
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Company</th>
                            <th>Shares Owned</th>
                            <th>Average Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = mysqli_fetch_assoc($portfolio)){ ?>
                            <tr>
                                <td><?php echo $p['company_name']; ?></td>
                                <td><?php echo $p['shares_owned']; ?></td>
                                <td>₹ <?php echo $p['average_price']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

</body>
</html>
