<?php
session_start();
include 'connect.php';

$id = $_SESSION['userId'];

$selectUser = $connection -> prepare("SELECT * FROM user WHERE id= ? LIMIT 1");
$selectUser -> bind_param("s", $id);
$selectUser -> execute();
$result= $selectUser -> get_result();
$result = mysqli_fetch_row($result);
$status = $result[7];
if($status == 0){
  echo "Online";
}else{
  echo "Busy";
}
?>
