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
   <title> Learn It | Quizzes </title>
   <link rel="stylesheet" href="css/myQuizzes.css">
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
           <h3 id="NotTitle"><?php echo $rowN['name'];?></h3>
           <h3 id="NotDesc"><?php echo $rowN['description'];?></h3>
           <h3 id="NotFrom"><?php echo "From User ";echo $rowN['fromUser'];?></h3>
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
       <button id="New"> Compile a New Quiz</button>
       <script>
       $('#New').click(function(){
           window.location.href='newQuiz.php';
       })
       </script>
       <form method="post" id="Filter">
         <input type="text" id="CourseFilter" name="CourseFilter" placeholder="Search by course"></input>
         <input type="text" id="ConceptFilter" name="ConceptFilter" placeholder="Search by concept"></input>
         <input type="submit" id="SubmitFilter" name="SubmitFilter" value="Filter"></input>
       </form>
       <ul id="QuizList">
         <?php
         if(isset($_POST['SubmitFilter'])){
           $courseFilter = $_POST['CourseFilter'];
           $conceptFilter = $_POST['ConceptFilter'];
           if(!$courseFilter == "" && $conceptFilter == ""){
             $link = "myQuizzes.php?course=".$courseFilter;
             echo '<script type="text/javascript">';
             echo 'window.location.href="'.$link.'";';
             echo '</script>';
           }else if(!$conceptFilter == "" && $courseFilter == ""){
             $link = "myQuizzes.php?concept=".$conceptFilter;
             echo '<script type="text/javascript">';
             echo 'window.location.href="'.$link.'";';
             echo '</script>';
           }else if(!$conceptFilter == "" && !$courseFilter == ""){
             $link = "myQuizzes.php?concept=".$conceptFilter."&course=".$courseFilter;
             echo '<script type="text/javascript">';
             echo 'window.location.href="'.$link.'";';
             echo '</script>';
           }
         }

         if(isset($_GET['concept']) && !isset($_GET['course'])){
            $theConcept = $_GET['concept'];
            $theConcept = urldecode($theConcept);
            $sql = mysqli_query($connection, "SELECT DISTINCT Q.type AS type, Q.creatorId AS creatorId, U.first_name AS firstName, U.last_name AS lastName, C.name AS courseName,Q.id AS Qid, Q.name AS questionName, Q.description AS description FROM quiz Q INNER JOIN user U ON Q.creatorId = U.id INNER JOIN course C ON Q.courseId = C.id LEFT JOIN hasquestion H ON H.quizId = Q.id INNER JOIN enrolled E ON E.courseId = C.id WHERE E.studentId = '$id' AND H.questionId IN (SELECT Q.id FROM concept C INNER JOIN question Q ON C.questionId = Q.id  WHERE C.name = '$theConcept')");
         }else if(isset($_GET['course']) && !isset($_GET['concept'])){
            $theCourse = $_GET['course'];
            $theCourse = urldecode($theCourse);
            $sql = mysqli_query($connection, "SELECT DISTINCT Q.type AS type, Q.creatorId AS creatorId, U.first_name AS firstName, U.last_name AS lastName, C.name AS courseName,Q.id AS Qid, Q.name AS questionName, Q.description AS description FROM quiz Q INNER JOIN user U ON Q.creatorId = U.id INNER JOIN course C ON Q.courseId = C.id LEFT JOIN hasquestion H ON H.quizId = Q.id INNER JOIN enrolled E ON E.courseId = C.id WHERE E.studentId = '$id' AND C.name = '$theCourse'");
         }else if(isset($_GET['concept']) && isset($_GET['course'])){
           $theConcept = $_GET['concept'];
           $theCourse = $_GET['course'];
           $theConcept = urldecode($theConcept);
           $theCourse = urldecode($theCourse);
           $sql = mysqli_query($connection, "SELECT DISTINCT Q.type AS type, Q.creatorId AS creatorId, U.first_name AS firstName, U.last_name AS lastName, C.name AS courseName,Q.id AS Qid, Q.name AS questionName, Q.description AS description FROM quiz Q INNER JOIN user U ON Q.creatorId = U.id INNER JOIN course C ON Q.courseId = C.id LEFT JOIN hasquestion H ON H.quizId = Q.id INNER JOIN enrolled E ON E.courseId = C.id WHERE E.studentId = '$id' AND H.questionId IN (SELECT Q.id FROM concept C INNER JOIN question Q ON C.questionId = Q.id  WHERE C.name = '$theConcept') AND C.name = '$theCourse'");
         }else{
           $sql = mysqli_query($connection, "SELECT DISTINCT Q.type AS type, Q.creatorId AS creatorId, U.first_name AS firstName, U.last_name AS lastName, C.name AS courseName,Q.id AS Qid, Q.name AS questionName, Q.description AS description FROM quiz Q INNER JOIN user U ON Q.creatorId = U.id INNER JOIN course C ON Q.courseId = C.id LEFT JOIN hasquestion H ON H.quizId = Q.id INNER JOIN enrolled E ON E.courseId = C.id WHERE E.studentId = '$id'");
         }
         while ($row = $sql->fetch_assoc()){
           $type = $row['type'];
           $creatorId = $row['creatorId'];
           $creatorName = $row['firstName']." ".$row['lastName'];
           $courseName = $row['courseName'];
           $quizName = $row['questionName'];
           $description = $row['description'];
           $Qid = $row['Qid'];

           $sql2 = mysqli_query($connection, "SELECT COUNT(questionId) AS questionNr FROM hasquestion WHERE quizId = $Qid");
           $row2 = $sql2->fetch_assoc();
           $questionNr = $row2['questionNr'];

           $userGrade = "-";
           $selectQuery = $connection -> prepare("SELECT * FROM takequiz WHERE userId = ? AND quizId = ?");
           $selectQuery -> bind_param("ss", $id, $Qid);
           $selectQuery -> execute();
           $result= $selectQuery -> get_result();
           if ($result->num_rows == 1){
             $row = mysqli_fetch_row($result);
             $userGrade = $row[2];
           }
           if($type == 2){
             $type = 'border: 3px solid #D9324A';
           }else if($type == 3){
             $type = 'border: 3px solid #DD8C24';
           }else{
             $type = 'border: 0px';
           }
          ?>
          <li>
            <table id="Quiz" style='<?php echo $type; ?>'>
              <tr id="QuizRow" onclick="openQuiz(<?php echo $Qid;?>,<?php if($userGrade == "-"){echo "11";}else{echo $userGrade;} ?>)">
                <td id="creatorNameColumn" title="Name of the creator of the Quiz (User Id = <?php echo $creatorId; ?>).">
                  <h3 id="creatorName"> <?php echo $creatorName; ?> </h3>
                </td>
                <td id="courseNameColumn" title="Name of the course">
                  <h3 id="courseName"> <?php echo $courseName; ?> </h3>
                </td>
                <td id="questionNameColumn" title="Name of the quiz">
                  <h3 id="questionName"> <?php echo $quizName; ?> </h3>
                </td>
                <td id="questionNumberColumn" title="Number of questions in the quiz">
                  <h3 id="questionNumber"> <?php echo $questionNr; ?> </h3>
                </td>
                <td id="questionDescriptionColumn" title="Description of the quiz">
                  <h3 id="questionDescription"> <?php echo $description; ?> </h3>
                </td>
                <td id="userGradeColumn" title="Your Grade">
                  <h3 id="userGrade"> <?php echo $userGrade; ?> </h3>
                </td>
                <td id="conceptColumn" title="Concepts that can be explored in this quiz">
                  <ul id="conceptList">
                    <?php
                    $sql2 = mysqli_query($connection, "SELECT C.name AS name FROM concept C INNER JOIN question Q ON C.questionId = Q.id INNER JOIN hasquestion H ON H.questionId = Q.id WHERE H.quizId = $Qid");
                    while ($row = $sql2->fetch_assoc()){
                      $name = $row['name'];
                     ?>
                    <li id="conceptElement">
                      <h3 id="concept"> <?php echo $name; ?> </h3>
                    </li>
                    <?php
                  }
                     ?>
                  </ul>
                </td>
              </tr>
            </table>
          </li>
          <?php
        }
           ?>
       </ul>
     </div>
     <input type="hidden" id="NotificationAlert"/>
     <input type="hidden" id="StatusAlert"/>
     <script>
     function openQuiz(courseId,userGrade){
       if(userGrade == 11){
         location.replace("completeQuiz.php?quiz=" + courseId);
       }else{
         location.replace("viewQuiz.php?quiz=" + courseId);
       }
     }
     </script>
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
