<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();
$selectQuery = $connection -> prepare("SELECT * FROM question WHERE creatorId = ?");
$selectQuery -> bind_param("s", $id);
$selectQuery -> execute();
$result2 = $selectQuery->get_result();
$all = $result2->num_rows;
$selectQuery1 = $connection -> prepare("SELECT * FROM question WHERE creatorId = ? AND approved = 0");
$selectQuery1 -> bind_param("s", $id);
$selectQuery1 -> execute();
$result1 = $selectQuery1->get_result();
$approved = $result1->num_rows;
$otherUserId = $_GET['who'];
$getUserName = mysqli_query($connection, "SELECT * FROM user WHERE id = '$otherUserId'");
$userData = $getUserName->fetch_assoc();
$firstName = $userData['first_name'];
$lastName = $userData['last_name'];
$profilePic = $userData['profilePic'];
if($profilePic == ''){
  $profilePic = 'Art/Default_Picture.png';
}
$userStatus = $userData['status'];
if($userStatus == 0){
  $userStatus = '#17cc06';
}else if($userStatus == 1){
  $userStatus = '#D9324A';
}
?>
<a href="chats.php" id="goBack"> ← Go Back </a>
<div id="UserData">
<div id="userStatus" style="background-color:<?php echo $userStatus ?>"></div>
<img src = "<?php echo $profilePic; ?>" alt = "Profile Picture" id="PP"></img>
<h3 id="Name"> <?php echo $firstName, " ", $lastName ?></h3>
