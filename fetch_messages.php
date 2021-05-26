<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$getChat = mysqli_query($connection, "SELECT * FROM chat INNER JOIN message ON message.chatId = chat.id WHERE user1Id = '$id' OR user2Id = '$id' GROUP BY user1Id, user2Id ORDER BY date DESC");
while ($chat = $getChat->fetch_assoc()){
  $chatId = $chat['chatId'];
  $user1 = $chat['user1Id'];
  $user2 = $chat['user2Id'];
  $getLatestMessage = mysqli_query($connection, "SELECT * FROM message WHERE chatId = $chatId ORDER BY date DESC");
  $mess1 = $getLatestMessage->fetch_assoc();
  if($user1 == $id){
    $getUserName = mysqli_query($connection, "SELECT * FROM user WHERE id = '$user2'");
    $otherUser = $user2;
  }else{
    $getUserName = mysqli_query($connection, "SELECT * FROM user WHERE id = '$user1'");
    $otherUser = $user1;
  }
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
  $text = $mess1['text'];
  $date = $mess1['date'];
  date_default_timezone_set('Europe/Bucharest');
  $currentDate = date('Y-m-d H:i:s');
  $date = new DateTime($date);
  $currentDate = new DateTime($currentDate);
  $interval = $date->diff($currentDate);
  ?>
  <a id="goToChat" href="message.php?who=<?php echo $otherUser; ?>">
  <li id="Message">
    <div id="userStatus" style="background-color:<?php echo $userStatus ?>"></div>
    <img src = "<?php echo $profilePic; ?>" alt = "Profile Picture" id="PP"></img>
    <h3 id="Name"> <?php echo $firstName, " ", $lastName ?></h3>
    <table>
      <th>
    <h3 id="Text"> <?php echo $text ?></h3>
  </th>
  <th>
    <h3 id="Date"> <?php echo $interval->format('%d')." Days ",$interval->format('%h')." Hours ".$interval->format('%i')." Minutes ", " ago"; ?></h3>
  </th>
</table>
  </li>
</a>
  <?php
}
?>
