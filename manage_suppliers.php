<?php
session_start();

require_once '../config/auth.php';
require_once '../config/permissions.php';
require_once '../config/db.php';

checkRole(['Admin', 'Supervisor']);

if(isset($_POST['save_supplier'])){

    $code = $_POST['code'];
    $name = $_POST['name'];
    $contact_person = $_POST['contact_person'];
    $contact_no = $_POST['contact_no'];
    $address = $_POST['address'];

    $stmt = $conn->prepare(
        "INSERT INTO nut_suppliers
        (code, name, contact_person, contact_no, address)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $code,
        $name,
        $contact_person,
        $contact_no,
        $address
    );

    $stmt->execute();

    $success = "Supplier Added Successfully";
}

$suppliers = mysqli_query(
    $conn,
    "SELECT * FROM nut_suppliers ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>

    <title>Manage Suppliers</title>

    <link rel="stylesheet"
          href="../css/dashboard.css">

</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">

    <h1>Manage Suppliers</h1>

    <?php if(isset($success)){ ?>

        <div style="
            background:#A6C297;
            padding:10px;
            margin-bottom:15px;
            border-radius:5px;
        ">
            <?php echo $success; ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input type="text"
               name="code"
               placeholder="Supplier Code"
               required>

        <input type="text"
               name="name"
               placeholder="Supplier Name"
               required>

        <input type="text"
               name="contact_person"
               placeholder="Contact Person">

        <input type="text"
               name="contact_no"
               placeholder="Contact Number">

        <textarea name="address"
                  placeholder="Address"></textarea>

        <br><br>

        <button type="submit"
                name="save_supplier"
                class="btn btn-success">

            Save Supplier

        </button>

    </form>
        <div class="table-container">

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Supplier Name</th>
                    <th>Contact Person</th>
                    <th>Contact No</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($suppliers)){ ?>

                <tr>

                    <td>
                        <?php echo $row['id']; ?>
                    </td>

                    <td>
                        <?php echo $row['code']; ?>
                    </td>

                    <td>
                        <?php echo $row['name']; ?>
                    </td>

                    <td>
                        <?php echo $row['contact_person']; ?>
                    </td>

                    <td>
                        <?php echo $row['contact_no']; ?>
                    </td>

                    <td>
                        <?php echo $row['status']; ?>
                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>