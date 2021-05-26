<?php
include 'connect.php';
session_start();
unset($_SESSION['userId']);
session_destroy();
header('location:index.php');
 ?>
