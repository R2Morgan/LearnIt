<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();
$courseId = $_GET['who'];
 ?>
<html>
<head>
  <title> Learn It | Enrolled Students </title>
  <link rel="stylesheet" href="css/getEnrolledList.css">
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
    <ul>
      <?php
      $sql = mysqli_query($connection, "SELECT S.id, S.first_name, S.last_name, C.Grade FROM enrolled C INNER JOIN user S ON S.id = C.studentId WHERE C.courseId = $courseId");
      while ($row = $sql->fetch_assoc()){

        $studentId = $row['id'];
        $studentFirstName = $row['first_name'];
        $studentLastName = $row['last_name'];

        $sqll = mysqli_query($connection, "SELECT * FROM points WHERE points.courseId = $courseId AND points.userId = $studentId");
        $roww = $sqll->fetch_assoc();
        $points = $roww['pointNr'];

        $sqlA = mysqli_query($connection, "SELECT * FROM course WHERE course.id = $courseId");
        $rowA = $sqlA->fetch_assoc();
        $isGamified = $rowA['gamifiedFriendly'];

        if($isGamified == 1 AND $points == 100){
          $points = ' + 1';
        }else{$points = '';}

        $studentGrade = $row['Grade'];
        if($studentGrade === NULL){$studentGrade = "Ungraded";}
        echo "<li id='listElement'>
        <table>
        <tr>
        <td id='idH'> $studentId </td>
        <td id='fnH'> $studentFirstName </td>
        <td id='lnH'> $studentLastName </td>
        <td id='gH'> $studentGrade $points </td>
        </tr>
        </table>
        </li>";
       ?>
      <?php
    }
    ?>
    </ul>
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
