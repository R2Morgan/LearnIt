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
  <title> Learn It | New Course </title>
  <link rel="stylesheet" href="css/newCourse.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
  <button id="Back"><image alt="Back" id="BackImage" src="Art/back.png"></image></button>
  <script>
  $(document).ready(function(){
    $("#Back").click(function(){
      location.replace("professor_c.php");
    });
  });
  </script>
  <div id="Screen">
    <form id="NewCourseForm" method="post">
    <input type="text" id="Name" name="Name" placeholder="Course Name"></input>
    <br>
    <input type="textarea" id="Description" name="Description" placeholder="The description of the course"></input>
    <br>
    <input type="text" id="Image" name="Image" placeholder="www.images.com/image.png"></input>
    <br>
    <input type="Submit" id="Submit" name="Submit" value="Create Course"></input>
  </form>
  </div>
  <?php
  if(isset($_POST['Submit'])){
    $name = $_POST['Name'];
    $description = $_POST['Description'];
    $image = $_POST['Image'];
    $sql = mysqli_query($connection, "INSERT INTO course(name, description, professorId, image) VALUES ('$name', '$description', $id, '$image')");
    header('location:professor_c.php');
  }
   ?>
</body>
</html>
