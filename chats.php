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
 ?>
 <html>
 <head>
   <title> Learn It | Chats </title>
   <link rel="stylesheet" href="css/chats.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 </head>
 <body>
     <div id="Menu">
       <h1 id="Logo"> LearnIt </h1>
       <div id="Status"> </div>
       <img alt="Profile Picture" src="
       <?php
       if($result['profilePic'] == '')
       {
         echo 'Art/Default_Picture.png';
       }else
       echo $result['profilePic'];
       ?>
       " id="Profile" />
       <input alt="Notification" type="image" src="Art/Notification.svg" id="Notification" />
       <h2 id="Title"> <?php
       $userId = $_SESSION['userId'];
       $selectQuery = $connection -> prepare("SELECT * FROM user WHERE id= ? LIMIT 1");
       $selectQuery -> bind_param("s", $userId);
       $selectQuery -> execute();
       $result= $selectQuery -> get_result();
       $result = mysqli_fetch_row($result);
       $title = $result[9];
       $first_name = $result[1];
       $last_name = $result[2];
       if($title == 1)
       {
         echo "Ms ", $first_name, " ", $last_name;
       }
       else{
         echo "Mr ", $first_name, " ", $last_name;
       }
        ?>
      </h2>
      <button id="Option">
        <h1 id="OptionTitle"> Questions </h1>
        <table>
          <th>
            <div id="Option1a">
                <h2 id="submittedNr"> <?php echo $all; ?> </h2>
                <h2 id="submitted"> Submitted </h2>
            </div>
        </th>
        <th>
          <div id="Option1b">
            <h2 id="pendingNr"> <?php echo $approved; ?> </h2>
            <h2 id="pending"> Pending </h2>
          </div>
        </th>
      </table>
      </button>
      <button id="Option1">
        <h1 id="OptionTitle"> Courses </h1>
      </button>
      <button id="Option2">
        <h1 id="OptionTitle"> Quizzes </h1>
      </button>
      <button id="Option3">
        <h1 id="OptionTitle"> Chats </h1>
      </button>
      <button id="Option4">
        <h1 id="OptionTitle"> Settings </h1>
      </button>
      <button id="LogOut">Log Out</button>
      <script>
      $('#Option').click(function(){
          window.location.href='myQuestions.php';
      })
      $('#Option1').click(function(){
          window.location.href='myCourses.php';
      })
      $('#Option2').click(function(){
          window.location.href='myQuizzes.php';
      })
      $('#Option3').click(function(){
          window.location.href='chats.php';
      })
      $('#Option4').click(function(){
          window.location.href='settings.php';
      })
      $('#LogOut').click(function(){
          window.location.href='kill_session.php';
      })
      </script>
     </div>
     <div id="MainScreen">
       <div id="myModal" class="modal">
         <span class="close">←</span>
         <?php
             $sqlN = mysqli_query($connection, "SELECT * FROM notification WHERE userId = '$id'");

             while ($rowN = $sqlN->fetch_assoc()){
         ?>

         <div id="Not">
           <h3 id="NotTitle"> <?php echo $rowN['name']; ?> </h3>
           <h3 id="NotDesc"> <?php echo $rowN['description']; ?> </h3>
           <h3 id="NotFrom"> <?php echo "From User "; echo $rowN['fromUser']; ?> </h3>
         </div>

       <?php } ?>
       </div>
       <script>
       var modal = document.getElementById("myModal");
       var btn = document.getElementById("Notification");
       var span = document.getElementsByClassName("close")[0];
       btn.onclick = function() {
         modal.style.display = "block";
       }
       span.onclick = function() {
         modal.style.display = "none";
       }
       window.onclick = function(event) {
         if (event.target != modal && event.target != btn) {
           modal.style.display = "none";
         }
       }
       </script>
       <form method="post" id="newMessage">
         <input type="submit" id="NewMess" name="NewMess" value="Start a new conversation!"></button>
         <input type="text" id="Username" name="Username" placeholder="exampleAccount"> </button>
         <input type="text" id="Mess" name="Mess" placeholder="Hello there!"> </text>
       </form>
       <?php
       if(isset($_POST['NewMess'])) {
          $username = $_POST['Username'];
          $mess = $_POST['Mess'];
          if($username != $id){
          if($username == '' || $mess == ''){
            echo "<p id='Error'> In order to start a new conversation, you must send a message to an existing user!</p>";
          }else{
            $selectUser = $connection->prepare("SELECT * FROM user WHERE id = ?");
            $selectUser -> bind_param("s", $username);
            $selectUser -> execute();
            $returnedUser = $selectUser->get_result();
            if ($returnedUser->num_rows == 0){
              echo "<p id='Error'> No such user exists! </p>";
            }else{
            $selectChats = $connection -> prepare("SELECT * FROM chat WHERE (user1Id = ? AND user2Id = ?) OR (user1Id = ? AND user2Id = ?)");
            $selectChats -> bind_param("ssss", $id, $username, $username, $id);
            $selectChats -> execute();
            $resultingChats = $selectChats->get_result();
            if ($resultingChats->num_rows == 1){
              echo "<p id='Error'> You already have a chat with this person!</p>";
          }else{
            $sql = "INSERT INTO chat (user1Id,user2Id) VALUES ('$id','$username')";
            $connection->query($sql);
            $selectNew = mysqli_query($connection, "SELECT * FROM chat WHERE user1Id = '$id' AND user2Id = '$username'");
            $selectNew = $selectNew->fetch_assoc();
            $chatId = $selectNew['id'];
            $insertMessage = $connection -> prepare("INSERT INTO message (sender, chatId, text, date) VALUES (?,?,?,CURRENT_TIMESTAMP)");
            $insertMessage -> bind_param("sss", $id, $chatId, $mess);
            $insertMessage -> execute();
            $insertNotification = $connection -> prepare("INSERT INTO notification (userId, name, fromUser, description, type) VALUES (?, 'New Message',?,?,'1')");
            $insertNotification -> bind_param("sss", $username, $id, $mess);
            $insertNotification -> execute();
          }
        }
       }
     }else{
       echo "<p id='Error'> You cannot send a chat to yourself! </p>";
     }
     }
       ?>
       <ul id="MessageList">
       <?php
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
     </ul>
     </div>
     <input type="hidden" id="NotificationAlert"/>
     <input type="hidden" id="StatusAlert"/>
     <script>
     var div = document.getElementById('Notification');
     function reload_notifications()
     {
       $.ajax({
         url: 'fetch_notifications.php',
         success:function(data){
           $("#NotificationAlert").html(data);
           if($("#NotificationAlert").text() == "No"){
             div.style.visibility ='hidden';
           }else{
             div.style.visibility = 'visible';
           }
         }
       });

     }
     function reload_messages()
     {
       $.ajax({
         url: 'fetch_messages.php',
         success:function(data){
           $("#MessageList").html(data);
         }
       });

     }
     var stat = document.getElementById('Status');
     function reload_status()
     {
       $.ajax({
         url: 'fetch_status.php',
         success:function(data){
           $("#StatusAlert").html(data);
           if($("#StatusAlert").text() == "Online"){
             stat.style.backgroundColor ='#17cc06';
           }else{
             stat.style.backgroundColor ='#D9324A';
           }
         }
       });
     }
     setInterval('reload_status()',1000);
     setInterval('reload_notifications()',1000);
     setInterval('reload_messages()',1000);
     </script>
 </body>
 </html>
