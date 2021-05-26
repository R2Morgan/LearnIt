<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();

$courseId = $_GET['id'];

$sql = mysqli_query($connection, "DELETE FROM question WHERE id = $courseId");
$connection->query($sql);

$select1 = mysqli_query($connection, "SELECT * FROM question WHERE id = $courseId");
$row1 = $select1->fetch_assoc();
$creatorId = $row1['creatorId'];
$questionName = $row1['text'];

$insertNotification = $connection -> prepare("INSERT INTO notification (userId, name, fromUser, description, type) VALUES (?, 'Rejected Question',?,'Your Question: $questionName has been rejected','2')");
$insertNotification -> bind_param("ss", $creatorId, $id);
$insertNotification -> execute();
header('location:studentQuestions.php');
 ?>
