<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "schoolManagement";

date_default_timezone_set('Asia/Kolkata');

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET time_zone = '+05:30'");
?>
