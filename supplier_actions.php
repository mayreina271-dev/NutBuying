<?php

include(__DIR__ . '/../../../config/db.php');

if(isset($_POST['ajax_action'])) {

    switch($_POST['ajax_action']) {

        case 'add_supplier':

            $code = $_POST['code'];
            $name = $_POST['name'];
            $contact_person = $_POST['contact_person'];
            $contact_no = $_POST['contact_no'];
            $address = $_POST['address'];

            $stmt = $conn->prepare("
                INSERT INTO nut_suppliers
                (code,name,contact_person,contact_no,address)
                VALUES (?,?,?,?,?)
            ");

            $stmt->bind_param(
                "sssss",
                $code,
                $name,
                $contact_person,
                $contact_no,
                $address
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

        case 'load_suppliers':

            $search = "%".$_POST['search']."%";

            $stmt = $conn->prepare("
                SELECT *
                FROM nut_suppliers
                WHERE name LIKE ?
                OR code LIKE ?
                ORDER BY id DESC
            ");

            $stmt->bind_param("ss",$search,$search);
            $stmt->execute();

            $result = $stmt->get_result();

            while($row = $result->fetch_assoc()) {

                echo "
                <tr>
                    <td>{$row['code']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['contact_person']}</td>
                    <td>{$row['contact_no']}</td>
                    <td>{$row['status']}</td>

                    <td>
                        <button
                            class='btn btn-warning btn-sm editBtn'
                            data-id='{$row['id']}'>
                            Edit
                        </button>

                        <button
                            class='btn btn-danger btn-sm toggleBtn'
                            data-id='{$row['id']}'>
                            Toggle
                        </button>
                    </td>
                </tr>
                ";
            }

        break;
    }
}
?>