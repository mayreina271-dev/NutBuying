<?php

include(__DIR__ . '/../../../config/db.php');

if(isset($_POST['ajax_action'])) {

    switch($_POST['ajax_action']) {

        case 'save_purchase':

            $supplier_id = $_POST['supplier_id'];
            $delivery_date = $_POST['delivery_date'];
            $truck_no = $_POST['truck_no'];
            $dr_no = $_POST['dr_no'];
            $gross = $_POST['gross_weight_kg'];
            $tare = $_POST['tare_weight_kg'];
            $grade = $_POST['grade'];
            $price = $_POST['price_per_kg'];
            $notes = $_POST['notes'];

            $duplicate = $conn->prepare("
                SELECT id
                FROM nut_purchases
                WHERE supplier_id=?
                AND dr_no=?
            ");

            $duplicate->bind_param(
                "is",
                $supplier_id,
                $dr_no
            );

            $duplicate->execute();

            $dupResult = $duplicate->get_result();

            if($dupResult->num_rows > 0) {

                echo json_encode([
                    "status" => "duplicate"
                ]);

                exit;
            }

            $stmt = $conn->prepare("
                INSERT INTO nut_purchases
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
                (?,?,?,?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "isssddsssi",
                $supplier_id,
                $delivery_date,
                $truck_no,
                $dr_no,
                $gross,
                $tare,
                $grade,
                $price,
                $notes,
                $_SESSION['user_id']
            );

            if($stmt->execute()) {

                echo json_encode([
                    "status" => "success"
                ]);

            } else {

                echo json_encode([
                    "status" => "error"
                ]);
            }

        break;
    }
}
?>