<?php
include '../config/db.php';

if(isset($_POST['application_id']) && isset($_POST['shares'])){

    $application_id = intval($_POST['application_id']);
    $shares = intval($_POST['shares']);

    // ================== GET APPLICATION ==================
    $app_query = mysqli_query($conn,
        "SELECT * FROM ipo_application WHERE application_id='$application_id'"
    );

    if(!$app_query || mysqli_num_rows($app_query) == 0){
        die("Invalid Application");
    }

    $app = mysqli_fetch_assoc($app_query);

    $applied_shares = $app['shares_applied'];
    $ipo_id = $app['ipo_id'];
    $user_id = $app['user_id'];

    // ================== GET IPO DETAILS ==================
    $ipo_query = mysqli_query($conn,
        "SELECT available_shares, price_per_share 
         FROM ipo WHERE ipo_id='$ipo_id'"
    );

    $ipo = mysqli_fetch_assoc($ipo_query);
    $available = $ipo['available_shares'];
    $price = $ipo['price_per_share'];

    // ================== VALIDATIONS ==================

    if($shares > $applied_shares){
        header("Location: dashboard.php?error=more_than_applied");
        exit();
    }

    if($shares > $available){
        header("Location: dashboard.php?error=not_available");
        exit();
    }

    $total_cost = $shares * $price;

    // ================== CHECK USER BALANCE ==================

    $user_query = mysqli_query($conn,
        "SELECT balance FROM users WHERE user_id='$user_id'"
    );

    $user = mysqli_fetch_assoc($user_query);

    if($user['balance'] < $total_cost){
        header("Location: dashboard.php?error=low_balance");
        exit();
    }

    // ================== START TRANSACTION ==================
    mysqli_begin_transaction($conn);

    try {

        // 1️⃣ Insert into allotment
        mysqli_query($conn,
            "INSERT INTO allotment (application_id, shares_allotted)
             VALUES ('$application_id', '$shares')"
        );

        // 2️⃣ Update application status
        mysqli_query($conn,
            "UPDATE ipo_application 
             SET status='APPROVED' 
             WHERE application_id='$application_id'"
        );

        // 3️⃣ Reduce IPO available shares
        mysqli_query($conn,
            "UPDATE ipo 
             SET available_shares = available_shares - $shares
             WHERE ipo_id='$ipo_id'"
        );

        // 4️⃣ Deduct user balance
        mysqli_query($conn,
            "UPDATE users 
             SET balance = balance - $total_cost
             WHERE user_id='$user_id'"
        );

        // 5️⃣ Add wallet transaction record
        mysqli_query($conn,
            "INSERT INTO wallet_transactions (user_id, type, amount)
             VALUES ('$user_id', 'IPO Purchase', '$total_cost')"
        );

        // 6️⃣ UPDATE PORTFOLIO PROPERLY

        $check_portfolio = mysqli_query($conn,
            "SELECT * FROM portfolio 
             WHERE user_id='$user_id' AND ipo_id='$ipo_id'"
        );

        if(mysqli_num_rows($check_portfolio) > 0){

            // Update existing shares
            mysqli_query($conn,
                "UPDATE portfolio
                 SET shares_owned = shares_owned + $shares
                 WHERE user_id='$user_id' AND ipo_id='$ipo_id'"
            );

        } else {

            // Insert new entry
            mysqli_query($conn,
                "INSERT INTO portfolio (user_id, ipo_id, shares_owned, average_price)
                 VALUES ('$user_id', '$ipo_id', '$shares', '$price')"
            );
        }

        mysqli_commit($conn);

        header("Location: dashboard.php?success=approved");
        exit();

    } catch (Exception $e) {

        mysqli_rollback($conn);
        die("Transaction Failed");
    }
}
?>


