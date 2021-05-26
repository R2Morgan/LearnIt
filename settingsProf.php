<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result3 = $sql->fetch_assoc();
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
   <title> Learn It | Settings </title>
   <link rel="stylesheet" href="css/settings.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 </head>
 <body>
   <div id="Menu">
     <h1 id="Logo"> LearnIt </h1>
     <div id="Status"> </div>
     <img alt="Profile Picture" src="
     <?php
     if($result3['profilePic'] == '')
     {
       echo 'Art/Default_Picture.png';
     }else
     echo $result3['profilePic'];
     ?>
     " id="Profile" />
     <input alt="Notification" type="image" src="Art/Notification.svg" id="Notification" />
     <h2 id="Title"><?php
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
     <table>
       <tr>
         <th>
           <div id="Settings">
             <form id='UpdateSettings' method='post'>
               <input type="text" id="S_Image" name='S_Image' placeholder='www.google.com/Image.png'>
               <br>
               <input type="text" id="S_FN" name='S_FN' placeholder='John'>
               <input type="text" id="S_LN" name='S_LN' placeholder='Smith'>
               <br>
               <input type="text" id="S_City" name='S_City' placeholder='Cluj-Napoca'>
               <select id="S_Faculty" name='S_Faculty'>
                 <option value="" disabled selected>Select your Faculty</option>
                 <?php
                     $sql = mysqli_query($connection, "SELECT name FROM faculty");
                     while ($row = $sql->fetch_assoc()){
                 ?>
                 <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                 <?php
                     }
                 ?>
               </select>
               <br>
               <select id="S_Status" name="S_Status">
                 <option value="" disabled selected>Select your Status</option>
                 <option value="0">Online</option>
                 <option value="1">Busy</option>
               </select>
               <select id="S_Gender" name="S_Gender">
                 <option value="" disabled selected>Select your Gender</option>
                 <option value="0">Male</option>
                 <option value="1">Female</option>
               </select>
               <br>
               <input type="text" id="S_Email" name='S_Email' placeholder='john.smith@gmail.com'>
               <br>
               <input type="text" id="S_Password" name='S_Password' placeholder='Password123'>
               <br>
               <input type="submit" id="S_Submit" name='S_Submit' value="Save Changes">
             </forms>
           </div>
         </th>
         <th>
           <div id="Info">
             <img alt="Profile Picture" src="
             <?php
             if($result3['profilePic'] == '')
             {
               echo 'Art/Default_Picture.png';
             }else
             echo $result3['profilePic'];
             ?>
             " id="ProfilePicture" />
             <table id="InfoTable">
               <?php
               $selectUser = $connection -> prepare("SELECT * FROM user WHERE id= ? LIMIT 1");
               $selectUser -> bind_param("s", $userId);
               $selectUser -> execute();
               $result= $selectUser -> get_result();
               $result = mysqli_fetch_row($result);
               $first_name = $result[1];
               $last_name = $result[2];
               $city = $result[4];
               $faculty = $result[5];
               $selectFaculty = $connection -> prepare("SELECT * FROM faculty WHERE id= ? LIMIT 1");
               $selectFaculty -> bind_param("s", $faculty);
               $selectFaculty -> execute();
               $result1= $selectFaculty -> get_result();
               $result1 = mysqli_fetch_row($result1);
               $faculty = $result1[1];
               $status = $result[7];
               $gender = $result[9];
               $email = $result[10];
               ?>
               <tr id="No1">
                 <th id="N1">
                   <h3>First Name</h3>
                 </th>
                 <th id="O1">
                   <h3><?php echo $first_name ?></h3>
                 </th>
               </tr>
               <tr id="No2">
                 <td id="N2">
                   <h3>Last Name</h3>
                 </td>
                 <td id="O2">
                   <h3><?php echo $last_name ?></h3>
                 </td>
               </tr>
               <tr id="No3">
                 <td id="N3">
                   <h3>City</h3>
                 </td>
                 <td id="O3">
                   <h3><?php echo $city ?></h3>
                 </td>
               </tr>
               <tr id="No4">
                 <td id="N4">
                   <h3>Faculty</h3>
                 </td>
                 <td id="O4">
                   <h3><?php echo $faculty ?></h3>
                 </td>
               </tr>
               <tr id="No5">
                 <td id="N5">
                   <h3>Status</h3>
                 </td>
                 <td id="O5">
                   <h3><?php if($status == "0") {echo "Online";} else echo "Busy"; ?></h3>
                 </td>
               </tr>
               <tr id="No6">
                 <td id="N6">
                   <h3>Gender</h3>
                 </td>
                 <td id="O6">
                   <h3><?php if($gender == "0") {echo "Male";} else echo "Female"; ?></h3>
                 </td>
               </tr>
               <tr id="No7">
                 <td id="N7">
                   <h3>Email</h3>
                 </td>
                 <td id="O7">
                   <h3><?php if($email == "") {echo "No Email";} else echo $email; ?></h3>
                 </td>
               </tr>
             </table>
           </div>
         </th>
       </tr>
     </table>
   </div>
   <?php
   if(isset($_POST['S_Submit'])) {

   $currentImage = $result[8];
   $currentFirstName = $first_name;
   $currentLastName = $last_name;
   $currentCity = $city;
   $currentFaculty = $faculty;
   $currentStatus = $status;
   $currentGender = $gender;
   $currentEmail = $email;
   $currentPassword = $result[3];

   if($_POST['S_Image'] != '') {
     $currentImage = $_POST['S_Image'];
   }

   if($_POST['S_FN'] != '') {
     $currentFirstName = $_POST['S_FN'];
   }

   if($_POST['S_LN'] != '') {
     $currentLastName = $_POST['S_LN'];
   }

   if($_POST['S_City'] != '') {
     $currentCity = $_POST['S_City'];
   }

   if(isset($_POST['S_Faculty'])) {
     $currentFaculty = $_POST['S_Faculty'];
   }

   if(isset($_POST['S_Status'])) {
     $currentStatus = $_POST['S_Status'];
   }

   if(isset($_POST['S_Gender'])) {
     $currentGender = $_POST['S_Gender'];
   }

   if($_POST['S_Email'] != '') {
     $currentEmail = $_POST['S_Email'];
   }

   if($_POST['S_Password'] != '') {
     $currentPassword = $_POST['S_Password'];
   }

   $selectFaculty = $connection -> prepare("SELECT * FROM faculty WHERE name= ? LIMIT 1");
   $selectFaculty -> bind_param("s", $currentFaculty);
   $selectFaculty -> execute();
   $result4= $selectFaculty -> get_result();
   $result4 = mysqli_fetch_row($result4);
   $currentFaculty = $result4[0];

   $sql = "UPDATE user SET first_name = '$currentFirstName', last_name = '$currentLastName', password = '$currentPassword', city = '$currentCity', faculty=$currentFaculty, status = '$currentStatus', profilePic = '$currentImage', title = '$currentGender', email = '$currentEmail' WHERE id = '$userId'";
   mysqli_query($connection, $sql);
   echo("<meta http-equiv='refresh' content='1'>");
  }
   ?>
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
