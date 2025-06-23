<?php
session_start();
$_SESSION['role']='';
$_SESSION['userID']='';
header("Location: index.html");
echo"<script>alert('You have been logged out.')</script>";
?>