<?php
include 'connect.php';
 ?>
 <html>
 <head>
   <title> Learn It | Welcome </title>
   <link rel="stylesheet" href="css/index.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 </head>
 <body>
   <div id="Header">
      <a href="index.php" id="Logo"> LearnIt </a>
      <h2><h2>
      <h2 id="HeaderSpace"> | </h2>
      <a href="courses.php" id="HeaderLink"> Courses </a>
      <h2 id="HeaderSpace"> | </h2>
      <a href="about.php" id="HeaderLink"> About </a>
      <h2 id="HeaderSpace"> | </h2>
      <a href="contact.php" id="HeaderLink"> Contact Us </a>
   </div>
   <button id="LogIn"> Log In </button>
   <h3 id="AccountText"> - Don't have an account? - </h3>
   <button id="Register"> Register Now </button>
   <div class='latestAnnouncement'>
     <h3 id="latestAnnouncement"> - <?php $sql = mysqli_query($connection, "SELECT title FROM latestAnnouncement"); echo $sql->fetch_assoc()['title'] ?> - <h3>
     <svg height="210" width="500">
        <line x1="0" y1="0" x2="400" y2="0" style="stroke:rgb(255,255,255);stroke-width:3" />
     </svg>
     <p id="Announcement">
       <?php $sql = mysqli_query($connection, "SELECT * FROM latestAnnouncement"); echo $sql->fetch_assoc()['desc'] ?>
     </p>
   </div>
   <br>
   <h4 id="Bottom1"> LearnIt </h4>
   <p id="Bottom2"> "Enhancing the Pandemic University Experience" </p>
   <script>
      $('#LogIn').click(function(){
          window.location.href='login.php';
      })
      $('#Register').click(function(){
          window.location.href='register.php';
      })
   </script>
 </body>
 </html>
