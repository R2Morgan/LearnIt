<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];

$name = $_POST['name'];

$selectQuery = $connection -> prepare("SELECT * FROM chat WHERE (user1Id = ? AND user2Id = ?) OR (user2Id = ? AND user1Id = ?) LIMIT 1");
$selectQuery -> bind_param("ssss", $id, $name, $name, $id);
$selectQuery -> execute();
$result= $selectQuery -> get_result();
if ($result->num_rows == 1){
  echo $name;
} else {
  $sql = mysqli_query($connection, "INSERT INTO chat(user1Id, user2Id) VALUES ($id, $name)");

  $selectNew = mysqli_query($connection, "SELECT * FROM chat WHERE (user1Id = $id AND user2Id = $name) OR (user1Id = $name AND user2Id = $id)");
  $selectNew = $selectNew->fetch_assoc();
  $chatId = $selectNew['id'];

  $insertMessage = $connection -> prepare("INSERT INTO message (sender, chatId, text, date) VALUES (?,?,?,CURRENT_TIMESTAMP)");
  $text = 'Connection achieved';
  $insertMessage -> bind_param("sss", $id, $chatId, $text);
  $insertMessage -> execute();
  echo $name;
}
 ?>
