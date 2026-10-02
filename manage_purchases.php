<?php
session_start();

require_once '../config/auth.php';
require_once '../config/permissions.php';
require_once '../config/db.php';

checkRole(['Admin', 'Supervisor', 'Operator']);


// SAVE PURCHASE
if(isset($_POST['save_purchase'])){

    $supplier_id = $_POST['supplier_id'];
    $delivery_date = $_POST['delivery_date'];
    $truck_no = $_POST['truck_no'];
    $dr_no = $_POST['dr_no'];
    $gross_weight_kg = $_POST['gross_weight_kg'];
    $tare_weight_kg = $_POST['tare_weight_kg'];
    $grade = $_POST['grade'];
    $price_per_kg = $_POST['price_per_kg'];
    $notes = $_POST['notes'];

    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "INSERT INTO nut_purchases
        (
            supplier_id,
            delivery_date,
            truck_no,
            dr_no,
            gross_weight_kg,
            tare_weight_kg,
            grade,
            price_per_kg,
            notes,
            created_by
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "isssddsdsi",
        $supplier_id,
        $delivery_date,
        $truck_no,
        $dr_no,
        $gross_weight_kg,
        $tare_weight_kg,
        $grade,
        $price_per_kg,
        $notes,
        $created_by
    );

    $stmt->execute();

    $success = "Purchase Recorded Successfully";
}


// FETCH SUPPLIERS
$suppliers = mysqli_query(
    $conn,
    "SELECT * FROM nut_suppliers
     WHERE status='Active'
     ORDER BY name ASC"
);


// FETCH PURCHASES
$purchases = mysqli_query(
    $conn,
    "SELECT
        np.*,
        ns.name AS supplier_name
     FROM nut_purchases np
     LEFT JOIN nut_suppliers ns
     ON np.supplier_id = ns.id
     ORDER BY np.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Manage Purchases</title>

    <link rel="stylesheet"
          href="../css/dashboard.css">

</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <h1>Manage Purchases</h1>

    <?php if(isset($success)){ ?>

        <div style="
            background:#A6C297;
            padding:12px;
            margin-bottom:15px;
            border-radius:5px;
        ">
            <?php echo $success; ?>
        </div>

    <?php } ?>


    <div class="table-container">

        <h2>Record Purchase</h2>

        <form method="POST">

            <select name="supplier_id" required>

                <option value="">
                    Select Supplier
                </option>

                <?php while($supplier = mysqli_fetch_assoc($suppliers)){ ?>

                    <option value="<?php echo $supplier['id']; ?>">

                        <?php echo $supplier['name']; ?>

                    </option>

                <?php } ?>

            </select>

            <br><br>

            <input type="date"
                   name="delivery_date"
                   required>

            <input type="text"
                   name="truck_no"
                   placeholder="Truck Number">

            <input type="text"
                   name="dr_no"
                   placeholder="DR Number">

            <input type="number"
                   step="0.01"
                   name="gross_weight_kg"
                   placeholder="Gross Weight (kg)"
                   required>

            <input type="number"
                   step="0.01"
                   name="tare_weight_kg"
                   placeholder="Tare Weight (kg)"
                   required>

            <select name="grade">

                <option value="Premium">
                    Premium
                </option>

                <option value="Standard">
                    Standard
                </option>

                <option value="Reject">
                    Reject
                </option>

            </select>

            <input type="number"
                   step="0.01"
                   name="price_per_kg"
                   placeholder="Price Per KG"
                   required>

            <textarea name="notes"
                      placeholder="Notes"></textarea>

            <br><br>

            <button type="submit"
                    name="save_purchase"
                    class="btn btn-success">

                Save Purchase

            </button>

        </form>

    </div>


    <div class="table-container">

        <h2>Purchase Records</h2>

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Truck No</th>
                    <th>Gross KG</th>
                    <th>Tare KG</th>
                    <th>Net KG</th>
                    <th>Grade</th>
                    <th>Price/KG</th>
                    <th>Total Amount</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($purchases)){ ?>

                <tr>

                    <td>
                        <?php echo $row['id']; ?>
                    </td>

                    <td>
                        <?php echo $row['supplier_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['delivery_date']; ?>
                    </td>

                    <td>
                        <?php echo $row['truck_no']; ?>
                    </td>

                    <td>
                        <?php echo $row['gross_weight_kg']; ?>
                    </td>

                    <td>
                        <?php echo $row['tare_weight_kg']; ?>
                    </td>

                    <td>
                        <?php echo $row['net_weight_kg']; ?>
                    </td>

                    <td>
                        <?php echo $row['grade']; ?>
                    </td>

                    <td>
                        ₱<?php echo number_format($row['price_per_kg'], 2); ?>
                    </td>

                    <td>
                        ₱<?php echo number_format($row['total_amount'], 2); ?>
                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>