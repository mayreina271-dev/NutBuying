<?php

$conn = new mysqli("localhost", "root", "", "nutbuying");

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}
?>