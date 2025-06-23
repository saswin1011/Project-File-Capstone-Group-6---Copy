<?php
session_start();
include('connect.php');

if (!($_SESSION['role']=='admin')) {
    header("Location: index.html");
    exit();
}
?>