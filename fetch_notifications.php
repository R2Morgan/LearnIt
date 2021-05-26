<?php
session_start();
include 'connect.php';

$id = $_SESSION['userId'];

$results = $connection-> query("SELECT * FROM notification WHERE userId = '$id'");
if($results->num_rows == 0){
  echo "No";
}else{
  echo "Yes";
}
?>
