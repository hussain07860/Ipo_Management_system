<?php
include '../config/db.php';

// ================== STATISTICS ==================
$total_apps = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM ipo_application"));
$approved = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM ipo_application WHERE status='APPROVED'"));
$pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM ipo_application WHERE status='PENDING'"));

// ================== FETCH APPLICATIONS ==================
$query = "SELECT ia.application_id, u.name, i.company_name, 
          ia.shares_applied, ia.status
          FROM ipo_application ia
          JOIN users u ON ia.user_id = u.user_id
          JOIN ipo i ON ia.ipo_id = i.ipo_id
          ORDER BY ia.application_id DESC";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#eef2f7;">
<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand mb-0 h1">IPO Management System - Admin</span>
    <div>
        <a href="wallet_requests.php" class="btn btn-warning btn-sm">Wallet Requests</a>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<a href="update_price.php" class="btn btn-warning">
    Update IPO Price
</a>


<div class="container mt-5">

    <h2 class="mb-4">IPO Applications</h2>
<a href="add_ipo.php" class="btn btn-primary mb-3">
    ➕ Add IPO
</a>

    <!-- ALERTS -->
    <?php if(isset($_GET['error'])){ ?>

    <?php if($_GET['error'] == 'more_than_applied'){ ?>
        <div class="alert alert-danger">
            ❌ Cannot approve more than applied shares!
        </div>
    <?php } ?>

    <?php if($_GET['error'] == 'not_available'){ ?>
        <div class="alert alert-danger">
            ❌ Not enough IPO shares available!
        </div>
    <?php } ?>

    <?php if($_GET['error'] == 'insufficient_balance'){ ?>
        <div class="alert alert-danger">
            ❌ User does not have sufficient wallet balance!
        </div>
        <?php if($_GET['error'] == 'low_balance'){ ?>
<div class="alert alert-danger">
❌ User does not have enough balance!
</div>
<?php } ?>

    <?php } ?>

<?php } ?>


    <!-- STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Total Applications</h5>
                    <h3><?php echo $total_apps; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Approved</h5>
                    <h3><?php echo $approved; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Pending</h5>
                    <h3><?php echo $pending; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow p-4">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>IPO</th>
                    <th>Applied Shares</th>
                    <th>Status</th>
                    <th width="250">Action</th>
                </tr>
            </thead>
            <tbody>

            <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) {

                    echo "<tr>";
                    echo "<td>".$row['application_id']."</td>";
                    echo "<td>".$row['name']."</td>";
                    echo "<td>".$row['company_name']."</td>";
                    echo "<td>".$row['shares_applied']."</td>";

                    if($row['status'] == 'APPROVED'){
                        echo "<td><span class='badge bg-success'>Approved</span></td>";
                        echo "<td><span class='text-success fw-bold'>Completed</span></td>";
                    } else {
                        echo "<td><span class='badge bg-warning text-dark'>Pending</span></td>";
                        echo "<td>
                                <form method='POST' action='approve.php' class='d-flex gap-2'>
                                    <input type='hidden' name='application_id' value='".$row['application_id']."'>
                                    <input type='number' name='shares' class='form-control' placeholder='Shares' required>
                                    <button class='btn btn-success btn-sm'>Approve</button>
                                </form>
                              </td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No Applications Found</td></tr>";
            }
            ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>

