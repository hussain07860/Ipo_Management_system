<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../auth/admin_login.php");
    exit();
}

// Approve request
if(isset($_GET['approve'])){

    $request_id = $_GET['approve'];

    mysqli_begin_transaction($conn);

    $req = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM wallet_requests WHERE request_id='$request_id' FOR UPDATE"
    ));

    if($req && $req['status'] == 'PENDING'){

        $user_id = $req['user_id'];
        $amount = $req['amount'];

        mysqli_query($conn,
            "UPDATE users SET balance = balance + '$amount'
             WHERE user_id='$user_id'"
        );

        mysqli_query($conn,
            "UPDATE wallet_requests
             SET status='APPROVED'
             WHERE request_id='$request_id'"
        );

        mysqli_commit($conn);
    } else {
        mysqli_rollback($conn);
    }

    header("Location: wallet_requests.php");
    exit();
}

$requests = mysqli_query($conn,
    "SELECT wr.*, u.name
     FROM wallet_requests wr
     JOIN users u ON wr.user_id = u.user_id
     ORDER BY wr.request_date DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wallet Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

<h3>Wallet Balance Requests</h3>

<table class="table table-bordered">
<tr>
    <th>User</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($requests)){ ?>
<tr>
    <td><?php echo $row['name']; ?></td>
    <td>₹ <?php echo $row['amount']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <?php if($row['status'] == 'PENDING'){ ?>
            <a href="?approve=<?php echo $row['request_id']; ?>" 
               class="btn btn-success btn-sm">Approve</a>
        <?php } else { ?>
            -
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

<a href="dashboard.php" class="btn btn-secondary">Back</a>

</div>
</body>
</html>
