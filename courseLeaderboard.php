<?php

include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];

$CourseId = $_GET['id'];

$sql = mysqli_query($connection, "SELECT * FROM course WHERE id = $CourseId");
$row = $sql->fetch_assoc();
$courseName = $row['name'];

?>
<html>
<head>
  <title> Learn It | <?php echo $courseName; ?> Leaderboard </title>
  <link rel="stylesheet" href="css/leaderboard.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
  <button id="Back"> < Go Back </button>
  <h1 id="Title"> <?php echo $courseName; ?> </h1>
  <script>
  $('#Back').click(function(){
      window.location.href='myCourses.php';
  })
  </script>
  <ul>
  <?php
      $sql = mysqli_query($connection, "SELECT user.first_name AS first_name, user.last_name AS last_name, pointNr FROM points INNER JOIN user ON user.id = points.userId WHERE courseId = $CourseId ORDER BY pointNr DESC");
      $int = 0;
      while ($row = $sql->fetch_assoc()){
        $name = $row['first_name'].' '.$row['last_name'];
        $points = $row['pointNr'];
        $int++;
  ?>
  <li>
    <div id="Entry">
      <h3 id="Number"><?php echo $int; ?>
      </h3>
      <h3 id="Name"><?php echo $name; ?>
      </h3>
      <h3 id="Points"><?php echo $points; echo ' Points'; ?>
      </h3>
    </div>
  </li>
  <?php
      }
  ?>
</ul>
</body>
</html>
