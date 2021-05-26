<?php
$servername = "localhost";
$user = "root";
$pass = "";
$database = "learnit";
$connection = new mysqli($servername, $user, $pass, $database);
if (! $connection) {
  die("Connection failed: " . mysqli_connect_error());
}
 ?>
