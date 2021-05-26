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
$messageList = mysqli_query($connection, "SELECT * FROM message WHERE message.chatId IN (SELECT id FROM chat WHERE (chat.user1Id = '$otherUserId' AND chat.user2Id = '$id') OR (chat.user1Id = '$id' AND chat.user2Id = '$otherUserId'))");
while ($message = $messageList->fetch_assoc()){
  $chatId = $message['chatId'];
  $sender = $message['sender'];
  if($sender == $id){
    $sender = 'Message0';
  }else{
    $sender = 'Message1';
  }
  $text = $message['text'];
  $date = $message['date'];
 ?>
 <li id="<?php echo $sender; ?>">
   <h3 id = "Text"> <?php echo $text ?> </h3>
   <h4 id = "Date"> <?php echo $date ?> </h4>
 </li>
 <?php
}
?>
