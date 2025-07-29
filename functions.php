<?php
session_start();
include "config.php";


function checkLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
}


function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
