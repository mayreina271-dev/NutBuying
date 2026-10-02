<?php

function logAction(
    $conn,
    $user_id,
    $type,
    $details
){

    $stmt = $conn->prepare(

        "INSERT INTO user_logs
        (user_id, action_type, action_details)

        VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "iss",
        $user_id,
        $type,
        $details
    );

    $stmt->execute();
}
?>