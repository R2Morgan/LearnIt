<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();

$courseId = $_GET['id'];

$sql = mysqli_query($connection, " UPDATE question SET approved = 1 WHERE id = $courseId");

$select1 = mysqli_query($connection, "SELECT * FROM course INNER JOIN question ON question.courseId = course.id WHERE question.id = $courseId");
$row1 = $select1->fetch_assoc();
$cId = $row1['courseId'];

$select1 = mysqli_query($connection, "SELECT * FROM question WHERE id = $courseId");
$row1 = $select1->fetch_assoc();
$creatorId = $row1['creatorId'];
$questionName = $row1['text'];

$sql = mysqli_query($connection, "UPDATE points SET pointNr = pointNr + 5 WHERE userId = '$creatorId' AND courseId = $cId");

$insertNotification = $connection -> prepare("INSERT INTO notification (userId, name, fromUser, description, type) VALUES (?, 'Approved Question',?,'Your Question: $questionName has been approved','2')");
$insertNotification -> bind_param("ss", $creatorId, $id);
$insertNotification -> execute();

header('location:studentQuestions.php');
 ?>
