<?php
session_start();

// Include database connection
include '../config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

// Get user input
$user_id = $_SESSION['user_id'];
$ipo_id = $_POST['ipo_id'];
$shares = (int) $_POST['shares'];

// Start transaction (BEGIN)
mysqli_begin_transaction($conn);

try {

    // ===============================
    // STEP 1: Fetch IPO details (LOCK row)
    // FOR UPDATE prevents other users from modifying this row
    // ===============================
    $ipo_result = mysqli_query($conn,
        "SELECT * FROM ipo WHERE ipo_id='$ipo_id' FOR UPDATE"
    );
    $ipo = mysqli_fetch_assoc($ipo_result);

    if(!$ipo){
        throw new Exception("IPO not found");
    }

    $price = $ipo['price_per_share'];
    $available_shares = $ipo['available_shares'];

    // Calculate total cost
    $total_cost = $price * $shares;

    // ===============================
    // STEP 2: Fetch user balance (LOCK row)
    // ===============================
    $user_result = mysqli_query($conn,
        "SELECT balance FROM users WHERE user_id='$user_id' FOR UPDATE"
    );
    $user = mysqli_fetch_assoc($user_result);

    if(!$user){
        throw new Exception("User not found");
    }

    $balance = $user['balance'];

    // ===============================
    // STEP 3: Validation checks (CONSISTENCY)
    // ===============================
    if($shares <= 0){
        throw new Exception("Invalid share quantity");
    }

    if($shares > $available_shares){
        throw new Exception("Not enough shares available");
    }

    if($total_cost > $balance){
        throw new Exception("Insufficient wallet balance");
    }

    // ===============================
    // STEP 4: Deduct user balance
    // ===============================
    $update_user = mysqli_query($conn,
        "UPDATE users
         SET balance = balance - '$total_cost'
         WHERE user_id='$user_id'"
    );

    if(!$update_user){
        throw new Exception("Failed to update balance");
    }

    // ===============================
    // STEP 5: Reduce available IPO shares
    // ===============================
    $update_ipo = mysqli_query($conn,
        "UPDATE ipo
         SET available_shares = available_shares - '$shares'
         WHERE ipo_id='$ipo_id'"
    );

    if(!$update_ipo){
        throw new Exception("Failed to update IPO shares");
    }

    // ===============================
    // STEP 6: Insert IPO application
    // ===============================
    $insert_app = mysqli_query($conn,
        "INSERT INTO ipo_application (user_id, ipo_id, shares_applied, status)
         VALUES ('$user_id', '$ipo_id', '$shares', 'PENDING')"
    );

    if(!$insert_app){
        throw new Exception("Failed to insert application");
    }

    // ===============================
    // STEP 7: COMMIT TRANSACTION (DURABILITY)
    // All changes are saved permanently
    // ===============================
    mysqli_commit($conn);

    // Redirect after success
    header("Location: dashboard.php");
    exit();

} catch (Exception $e) {

    // ===============================
    // STEP 8: ROLLBACK TRANSACTION (ATOMICITY)
    // Undo all changes if any error occurs
    // ===============================
    mysqli_rollback($conn);

    echo "Transaction Failed: " . $e->getMessage();
}
?>