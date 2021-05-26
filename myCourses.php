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
   <title> Learn It | Welcome </title>
   <link rel="stylesheet" href="css/myCourses.css">
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
       <form id="NewCourse" method="post">
        <input type="text" id="CourseId" name='CourseId' placeholder='Course Name or Identification'>
        <input type="image" id="SubmitCourse" name='SubmitCourse' alt='Enroll in a new course' alt="Submit" src="Art/Enroll.png" title="Enroll in a new course">
        <?php
        if(isset($_POST['CourseId'])){
          $course = $_POST['CourseId'];

          $selectQuery = $connection -> prepare("SELECT * FROM course WHERE id=? OR name=? LIMIT 1");
          $selectQuery -> bind_param("ss", $course, $course);
          $selectQuery -> execute();
          $result= $selectQuery -> get_result();
          if ($result->num_rows == 1){
            if(!is_numeric($course)){
              $selectCourse = mysqli_query($connection, "SELECT * FROM course WHERE name='$course'");
              $selectCourse = $selectCourse->fetch_assoc();
              $course = $selectCourse['id'];
            }
            $selectQuery = $connection -> prepare("SELECT * FROM enrolled WHERE studentId=? AND courseId=? LIMIT 1");
            $selectQuery -> bind_param("ss", $id, $course);
            $selectQuery -> execute();
            $result= $selectQuery -> get_result();
            if ($result->num_rows == 1){
              echo "<p id='error'> You are already enrolled in this course! </p>";
            }else{
              $sql = mysqli_query($connection, "INSERT INTO enrolled(studentId, courseId) VALUES ('$id', $course)");
              $sql = mysqli_query($connection, "INSERT INTO points(userId, courseId, pointNr) VALUES ('$id', $course, 0)");
            }
          }else{
          echo "<p id='error'> No course found! </p>";
        }
      }
         ?>
        </form>
        <ul id="List">
              <?php
                  $sql = mysqli_query($connection, "SELECT * FROM course C INNER JOIN enrolled E ON E.courseId = C.id WHERE E.studentId = '$id'");
                  while ($row = $sql->fetch_assoc()){
                    $professorId = $row['professorId'];
                    $courseId = $row['id'];
                    $isGamified = $row['gamifiedFriendly'];
                    if($isGamified == 0){
                      $isGamified = '.';
                    }else{
                      $isGamified = '. Collect 100 and get a Bonus Point on your final Grade!';
                    }
                    $sql1 = mysqli_query($connection, "SELECT * FROM user WHERE id = '$professorId'");
                    $row1 = $sql1->fetch_assoc();
                    $sql2 = mysqli_query($connection, "SELECT COUNT(*) FROM enrolled WHERE courseId = $courseId");
                    $row2 = $sql2->fetch_assoc();
                    $sql3 = mysqli_query($connection, "SELECT * FROM points WHERE userId = '$id' && courseId = $courseId");
                    $row3 = $sql3->fetch_assoc();
                    $pointsNr = $row3['pointNr'];
                    $count = $row2['COUNT(*)'];
                    $grade = $row['Grade'];
              ?>
              <li>
                <table id="Course">
                  <tr>
                    <td id="TitleColumn">
                      <a id="CourseTitle" href="courseLeaderboard.php?id=<?php echo $courseId; ?>" title="<?php echo 'You currently have '; echo $pointsNr; echo ' Points'; echo $isGamified; ?>" name='<?php echo $courseId;?>'><?php echo $row['name']; ?>
                    </td>
                    <td id="ProfessorColumn">
                      <input type="button" id="Professor" value="<?php echo 'Professor', ' ' , $row1['first_name'], ' ', $row1['last_name']; ?>" name='<?php echo $row1['id']?>' title="Start a chat!"></input>
                    </td>
                    <td id="CountColumn">
                      <h3 id="Count"><?php echo $count; echo ' Students Enrolled'; ?>
                    </td>
                    <td id="DescriptionColumn">
                      <h3 id="Description"><?php echo $row['description'] ?>
                    </td>
                    <td id="CountColumn">
                      <?php if($isGamified == '.'){$hasExtra = ''; }else{if($pointsNr == 100){ $hasExtra = '. You also have a Bonus Point'; }else{ $hasExtra = '. You do not have enough for a Bonus Point';}}?>
                      <h3 id="Count" title="Your Grade<?php echo $hasExtra; ?>"><?php if($grade === NULL){echo "No Grade";}else{echo $grade;} ?>
                    </td>
                    <td id="ImageColumn">
                      <img id="Image" alt="Course Image" src="<?php if($row['image'] == ''){echo 'Art/No_Picture.png';}else{echo $row['image'];} ?>"></img>
                    </td>
                  </tr>
                </table>
              </li>
              <?php
                  }
              ?>
        </ul>
        <h3 id="Divisive" title="Based on Previously Explored Concepts"> Recommended Courses </h3>
        <ul>
          <?php
          $sqlNew = mysqli_query($connection, "SELECT concept.courseId FROM concept WHERE concept.courseId NOT IN (SELECT enrolled.courseId FROM enrolled WHERE enrolled.studentId = '$id') AND concept.name IN (SELECT concept.name FROM concept WHERE concept.name IN (SELECT concept.name FROM concept WHERE concept.courseId IN (SELECT enrolled.courseId FROM enrolled WHERE enrolled.studentId = '$id')))");
          while ($rowNew = $sqlNew->fetch_assoc()){
            $courseId = $rowNew['courseId'];
            $sqlNewNew = mysqli_query($connection, "SELECT * FROM course WHERE id = $courseId");
            $rowNewNew = $sqlNewNew->fetch_assoc();
            $recId = $rowNewNew['id'];
            $recName = $rowNewNew['name'];
            $recProf = $rowNewNew['professorId'];
            $recDesc = $rowNewNew['description'];
            $recImg = $rowNewNew['image'];

            $sqlNN = mysqli_query($connection, "SELECT * FROM user WHERE id = '$recProf'");
            $rowNN = $sqlNN->fetch_assoc();
            $recProf = $rowNN['first_name'].' '.$rowNN['last_name'];
           ?>
           <li id="RecLi">
             <table>
               <tr>
                 <td id="RecId">
                   <h3 id="RI"> <?php echo $recId; ?>
                 </td>
                 <td id="RecName">
                   <h3 id="RN"> <?php echo $recName; ?>
                 </td>
                 <td id="RecProf">
                   <h3 id="RP"> <?php echo 'Professor '; echo $recProf; ?>
                 </td>
                 <td id="RecDesc">
                   <h3 id="RD"> <?php echo $recDesc; ?>
                 </td>
                 <td id="RecImg">
                   <img id="RecImage" alt="Course Image" src=<?php echo $recImg; ?>></img>
                 </td>
               </tr>
             </table>
           </li>
           <?php
         }
            ?>
        </ul>
        <div id="space"></div>
     </div>
     <script>
     $(document).ready(function(){
       $("#Professor").click(function(){
         var name = $('#Professor').attr('name');
         $.ajax({
           url: 'chatWithAProf.php',
           data: {name: name},
           type: "POST",
           success:function(data){
             var result = data;
             location.replace("message.php?who="+data);
           }
         });
       });
       $("#CourseTitle").click(function(){
         var name = $('#CourseTitle').attr('name');
         location.replace("courseLeaderboard.php?id="+name);
       });
     });
     </script>
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
