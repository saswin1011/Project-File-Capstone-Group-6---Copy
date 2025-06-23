<?php
session_start();
include('connect.php');

if (!($_SESSION['role']=='user')) {
    header("Location: index.html");
    exit();
}
?>