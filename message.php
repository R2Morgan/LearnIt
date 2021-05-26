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
 ?>
 <html>
 <head>
   <title> Learn It | Chats </title>
   <link rel="stylesheet" href="css/message.css">
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
       <div id="HeadBar">
         <?php
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
         </div>
       </div>
       <ul id="MessageList">
         <?php
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
        </ul id="MssList">
        <form method="post" id="NewMessage">
          <input type="text" name="NewText" id="NewText"></input>
          <input type="submit" name="SendText" id="SendText" value="↑"></input>
        </form>
        <?php
        if(isset($_POST['SendText'])){
        $mess = $_POST['NewText'];
        $insertMessage = $connection -> prepare("INSERT INTO message (sender, chatId, text, date) VALUES (?,?,?,CURRENT_TIMESTAMP)");
        $insertMessage -> bind_param("sss", $id, $chatId, $mess);
        $insertMessage -> execute();
        $insertNotification = $connection -> prepare("INSERT INTO notification (userId, name, fromUser, description, type) VALUES (?, 'New Message',?,?,'1')");
        $insertNotification -> bind_param("sss", $otherUserId, $id, $mess);
        $insertNotification -> execute();
      }
         ?>
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
     function reload_headbar()
     {
       $.ajax({
         url: 'fetch_headbar.php?who=<?php echo $otherUserId; ?>',
         success:function(data){
           $("#HeadBar").html(data);
         }
       });

     }
     function reload_messages()
     {
       $.ajax({
         url: 'fetch_mess.php?who=<?php echo $otherUserId; ?>',
         success:function(data){
           $("#MessageList").html(data);
         }
       });
     }
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
     setInterval('reload_headbar()',1000);
     setInterval('reload_notifications()',1000);
     setInterval('reload_messages()',1000);
     </script>
 </body>
 </html>
