<?php 
session_start();
session_destroy();
//echo "<script>alert('Sesion Cerrada'); window.location.href='login.php';</script>";
header('Location: login.php');
exit;
?>