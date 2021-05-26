<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();
 ?>

 <html>
 <head>
   <title> Learn It | Welcome </title>
   <link rel="stylesheet" href="css/professor_c.css">
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
       <button id="NewCourse"> Create a New Course </button>
       <script>
       $(document).ready(function(){
         $("#NewCourse").click(function(){
           location.replace("newCourse.php");
         });
       });
       </script>
        <ul id="List">
              <?php
                  $sql = mysqli_query($connection, "SELECT * FROM course C WHERE professorId = $id");
                  while ($row = $sql->fetch_assoc()){
                    $courseId = $row['id'];
                    $sql1 = mysqli_query($connection, "SELECT * FROM user WHERE id = $id");
                    $row1 = $sql1->fetch_assoc();
                    $sql2 = mysqli_query($connection, "SELECT COUNT(*) FROM enrolled WHERE courseId = $courseId");
                    $row2 = $sql2->fetch_assoc();
                    $count = $row2['COUNT(*)'];
              ?>
              <li>
                <table id="Course">
                  <tr>
                    <td id="TitleColumn">
                      <h3 id="CourseTitle"><?php echo $row['name']; ?>
                    </td>
                    <td id="ProfessorColumn">
                      <input type="button" id="Professor" value="<?php echo 'Professor', ' ' , $row1['first_name'], ' ', $row1['last_name']; ?>" name='<?php echo $row1['id']?>'></input>
                    </td>
                    <td id="CountColumn">
                      <a href="getEnrolledList.php?who=<?php echo $courseId; ?>" id="Count"> <?php echo $count; echo ' Enrolled'; ?></a>
                    </td>
                    <td id="DescriptionColumn">
                      <h3 id="Description"><?php echo $row['description'] ?>
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
        <script>
        $(document).ready(function(){
          function changePage(){
            //var name = $('#Count').attr('name');
            alert("Hey");
            //location.replace("getEnrolledList.php?who="+name);
          }
        });
        </script>
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
