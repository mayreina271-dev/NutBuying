<?php
session_start();

require_once '../config/auth.php';
require_once '../config/permissions.php';
require_once '../config/db.php';

checkRole(['Admin', 'Supervisor']);


// TOTAL PURCHASES
$totalPurchases = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM nut_purchases"
    )
)['total'];


// TOTAL SUPPLIERS
$totalSuppliers = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM nut_suppliers
         WHERE status='Active'"
    )
)['total'];


// TOTAL AMOUNT
$totalAmount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(total_amount) AS total
         FROM nut_purchases"
    )
)['total'];


// DAILY REPORT
$dailyReports = mysqli_query(
    $conn,
    "SELECT
        delivery_date,
        COUNT(*) AS total_transactions,
        SUM(net_weight_kg) AS total_net_kg,
        SUM(total_amount) AS total_sales
     FROM nut_purchases
     GROUP BY delivery_date
     ORDER BY delivery_date DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Reports</title>

    <link rel="stylesheet"
          href="../css/dashboard.css">

</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <h1>Nut Buying Reports</h1>


    <div class="cards">

        <div class="card">

            <h3>Total Purchases</h3>

            <h2>
                <?php echo $totalPurchases; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Suppliers</h3>

            <h2>
                <?php echo $totalSuppliers; ?>
            </h2>

        </div>


        <div class="card">

            <h3>Total Sales</h3>

            <h2>
                ₱<?php echo number_format($totalAmount, 2); ?>
            </h2>

        </div>

    </div>


    <div class="table-container">

        <h2>Daily Purchase Reports</h2>

        <table>

            <thead>

                <tr>

                    <th>Date</th>
                    <th>Total Transactions</th>
                    <th>Total Net KG</th>
                    <th>Total Sales</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($dailyReports)){ ?>

                <tr>

                    <td>
                        <?php echo $row['delivery_date']; ?>
                    </td>

                    <td>
                        <?php echo $row['total_transactions']; ?>
                    </td>

                    <td>
                        <?php echo number_format($row['total_net_kg'], 2); ?>
                    </td>

                    <td>
                        ₱<?php echo number_format($row['total_sales'], 2); ?>
                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>