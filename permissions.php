<?php

function checkRole($roles = []){

    if(!isset($_SESSION['role'])){

        header("Location: ../login.php");
        exit;
    }

    if(!in_array($_SESSION['role'], $roles)){

        die("Access Denied");
    }
}
?>