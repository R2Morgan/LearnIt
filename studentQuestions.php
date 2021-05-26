<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();
$selectQuery = $connection -> prepare("SELECT * FROM question Q INNER JOIN course C on C.id = Q.courseId WHERE C.professorId = ?");
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
   <title> Learn It | Student Questions </title>
   <link rel="stylesheet" href="css/studentQuestions.css">
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
       echo "Prof. ", $first_name, " ", $last_name;
        ?>
      </h2>
      <button id="Option">
        <h1 id="OptionTitle"> Questions </h1>
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
        window.location.href='professor_questions.php';
      })
      $('#Option1').click(function(){
        window.location.href='professor_c.php';
      })
      $('#Option2').click(function(){
        window.location.href='professor_q.php';
      })
      $('#Option3').click(function(){
          window.location.href='chatsProf.php';
      })
      $('#Option4').click(function(){
          window.location.href='settingsProf.php';
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
       <button id="Back"> < Go Back </button>
       <script>
       $('#Back').click(function(){
           window.location.href='professor_questions.php';
       })
       </script>
       <ul id="List">
         <?php
             $id = $_SESSION['userId'];
             $sql = mysqli_query($connection, "SELECT Q.id AS id, creatorId, approved, Q.image AS image, text, answer1, answer2, answer3, answer4, correctAnswers, explanation, difficulty FROM question Q INNER JOIN course C on C.id = Q.courseId WHERE C.professorId = $id");
             if($result2->num_rows > 0)
             {while ($row = $sql->fetch_assoc()){
               $questionId = $row['id'];
         ?>
         <li>
           <table id="QuestionTable">
           <tr id="Question">
             <?php $status = $row['approved'];
             if($status == 0)
             $status = 'Open' ;
             else
             $status = 'Approved'; ?>
             <td id=<?php echo $status; ?>><?php echo $status; ?>
             </td>
             <td id="Text"><?php echo $row['text']; ?>
             </td>
             <td>
               <?php $image = $row['image']; if($image != ''){?>
             <img id="Image" src="<?php echo $row['image']; ?>" alt="Question Image">
           </img>
         <?php } else { ?>
           <h3 id="Nothing"></h3> <?php } ?>
         </td>

             <td id="<?php
             $correctAnswers = $row['correctAnswers'];
             if($correctAnswers == '1000' || $correctAnswers == '1100' || $correctAnswers == '1101' || $correctAnswers == '1110' || $correctAnswers == '1111' || $correctAnswers == '1010' || $correctAnswers == '1011' || $correctAnswers == '1001'){
               echo "Correct";
             } else echo "Answer";
             ?>"><?php echo $row['answer1'] ?>
             </td>
             <td id="<?php
             $correctAnswers = $row['correctAnswers'];
             if($correctAnswers == '1100' || $correctAnswers == '1110' || $correctAnswers == '1111' || $correctAnswers == '1101' || $correctAnswers == '0110' || $correctAnswers == '0111' || $correctAnswers == '0100' || $correctAnswers == '0101'){
               echo "Correct";
             } else echo "Answer";
             ?>"><?php echo $row['answer2'] ?>
             </td>
             <td id="<?php
             $correctAnswers = $row['correctAnswers'];
             if($correctAnswers == '0010' || $correctAnswers == '0011' || $correctAnswers == '0110' || $correctAnswers == '0111' || $correctAnswers == '1010' || $correctAnswers == '1011' || $correctAnswers == '1110' || $correctAnswers == '1111'){
               echo "Correct";
             } else echo "Answer";
             ?>"><?php echo $row['answer3'] ?>
             </td>
             <td id="<?php
             $correctAnswers = $row['correctAnswers'];
             if($correctAnswers == '0001' || $correctAnswers == '1001' || $correctAnswers == '0101' || $correctAnswers == '1101' || $correctAnswers == '0011' || $correctAnswers == '1011' || $correctAnswers == '0111' || $correctAnswers == '1111'){
               echo "Correct";
             } else echo "Answer";
             ?>"><?php echo $row['answer4'] ?>
             </td>
           <?php $difficulty = $row['difficulty'];
           if ($difficulty == 1){
             $difficulty = 'Easy';
           } else if($difficulty == 2){
             $difficulty = 'Medium';
           } else if($difficulty == 3){
             $difficulty = 'Hard';
           } ?>
           <td id=<?php echo $difficulty; ?>> <?php echo $difficulty; ?>
           </td>
           <td <?php if($status == 'Approved') {echo 'style="display:none"';}?>>
           <a href='approveQuestion.php?id=<?php echo $questionId; ?>' id="Approve" title='Approve this Question'><image href='Approve' alt="Approve" id="ApproveImage" src="Art/like.png"></image></a>
         </td>
           <td <?php if($status == 'Approved') {echo 'style="display:none"';}?>>
           <a href='rejectQuestion.php?id=<?php echo $questionId; ?>' id="Reject" title='Reject this Question'><image href='Reject' alt="Reject" id="RejectImage" src="Art/dislike.png"></image></a>
           </td>
         </tr>
         </table>
         </li>
         <?php
       }}
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
     </script>
 </body>
 </html>
