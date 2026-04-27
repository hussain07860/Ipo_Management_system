<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ================= SELECT IPO ================= */
$selected_ipo = isset($_GET['ipo_id']) ? $_GET['ipo_id'] : 1;

/* ================= FETCH PRICE HISTORY ================= */
$history = mysqli_query($conn,
    "SELECT share_price, trade_date
     FROM price_history
     WHERE ipo_id = '$selected_ipo'
     ORDER BY trade_date ASC"
);

$dates = [];
$prices = [];

while ($row = mysqli_fetch_assoc($history)) {
    $dates[] = date("M d", strtotime($row['trade_date']));
    $prices[] = $row['share_price'];
}

/* ================= TREND LOGIC ================= */
$trend = "No Data";
$trend_color = "secondary";

if(count($prices) >= 2){
    $last = $prices[count($prices)-1];
    $prev = $prices[count($prices)-2];

    if($last > $prev){
        $trend = "Price Increasing ↑";
        $trend_color = "success";   // Green
    }
    elseif($last < $prev){
        $trend = "Price Decreasing ↓";
        $trend_color = "danger";    // Red
    }
    else{
        $trend = "Price Stable →";
        $trend_color = "warning";   // Yellow
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Price History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color:#f4f6f9;">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">IPO Price History</span>
    <a href="home.php" class="btn btn-light btn-sm">Back</a>
</nav>

<div class="container mt-4">

    <!-- IPO SELECT -->
    <h4>Select IPO</h4>

    <form method="GET" class="mb-4">
        <select name="ipo_id" class="form-select" onchange="this.form.submit()">
            <?php
            $all_ipos = mysqli_query($conn, "SELECT ipo_id, company_name FROM ipo");
            while ($ipo = mysqli_fetch_assoc($all_ipos)) {
                $selected = ($ipo['ipo_id'] == $selected_ipo) ? "selected" : "";
                echo "<option value='{$ipo['ipo_id']}' $selected>{$ipo['company_name']}</option>";
            }
            ?>
        </select>
    </form>

    <!-- TREND BADGE -->
    <div class="mb-3">
        <span class="badge bg-<?php echo $trend_color; ?>" style="font-size:16px;">
            <?php echo $trend; ?>
        </span>
    </div>

    <!-- GRAPH -->
    <div class="card shadow p-4">
        <canvas id="priceChart"></canvas>
    </div>

</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('priceChart');

const trendColor = "<?php
if($trend_color == 'success') echo '#28a745';
elseif($trend_color == 'danger') echo '#dc3545';
else echo '#ffc107';
?>";

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Share Price',
            data: <?php echo json_encode($prices); ?>,
            borderColor: trendColor,
            backgroundColor: trendColor,
            borderWidth: 3,
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});
</script>

</body>
</html>
