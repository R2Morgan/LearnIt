<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
?>
<html>
<head>
  <title> Learn It | New Question </title>
  <link rel="stylesheet" href="css/newQuestion.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
  <button id='Back'> Go Back </button>
  <script>
  $('#Back').click(function(){
      history.go(-1);
  })
  </script>
  <form id='Form' method='post'>
    <table>
      <th>
    <input type="text" id="Image" name='Image' placeholder='C:\Users\User\Desktop\Image.png'>
    <br>
    <textarea id="Text" placeholder="The Text of the Question" name='Text'></textarea>
    <br>
    <textarea id="Explanation" placeholder="An explaination" name='Explanation'></textarea>
    <br>
    <select id="Course" name="Course">
      <option value="" disabled selected>Select the Course</option>
      <?php
          $sql = mysqli_query($connection, "SELECT DISTINCT (name) FROM course INNER JOIN enrolled ON enrolled.courseId = course.id WHERE enrolled.studentId = $id OR course.professorId = $id");
          while ($row = $sql->fetch_assoc()){
      ?>
      <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
      <?php
          }
      ?>
    </select>
  </th>
  <th>
    <input type="checkbox" id="option1Checkbox" value="Option1" name='option1Checkbox'>
    <input type="checkbox" id="option2Checkbox" value="Option2" name='option2Checkbox'>
    <input type="checkbox" id="option3Checkbox" value="Option3" name='option3Checkbox'>
    <input type="checkbox" id="option4Checkbox" value="Option4" name='option4Checkbox'>
  </th>
  <th>
    <br>
    <input type="text" id="Option1" placeholder="Option 1" name='Option1'>
    <br>
    <input type="text" id="Option2" placeholder="Option 2" name='Option2'>
    <br>
    <input type="text" id="Option3" placeholder="Option 3" name='Option3'>
    <br>
    <input type="text" id="Option4" placeholder="Option 4" name='Option4'>
  </th>
</table>
    <label id="Difficulty"> Difficulty </label>
    <table>
    <th>
    <input type="radio" id="Easy" name="Radio" value="1" checked="True">
    <label id="easyLabel">Easy</label><br>
  </th>
    <th>
    <input type="radio" id="Medium" name="Radio" value="2">
    <label id="mediumLabel">Medium</label><br>
  </th>
    <th>
    <input type="radio" id="Hard" name="Radio" value="3">
    <label id="hardLabel">Hard</label><br>
  </th>
</table>
    <br>
    <textarea id="Concept" placeholder="CourseName:Concept, CourseName:Concept" name='Concept'></textarea>
    <textarea id="Reference" placeholder="www.google.com, www.w3schools.com" name='Reference'></textarea>
    <input id="Submit" name='Submit' type="submit" value="Send Question">
</form>
  </div>
  <?php
  if(isset($_POST['Submit'])){
    $id = $_SESSION['userId'];
    $ImageLink = $_POST['Image'];
    $Text = $_POST['Text'];
    $Explanation = $_POST['Explanation'];
    $RightAnswers = '';
    if(isset($_POST['option1Checkbox'])){
      $RightAnswers = $RightAnswers . '1';
    }else{
      $RightAnswers = $RightAnswers . '0';
    }
    if(isset($_POST['option2Checkbox'])){
      $RightAnswers = $RightAnswers . '1';
    }else{
      $RightAnswers = $RightAnswers . '0';
    }
    if(isset($_POST['option3Checkbox'])){
      $RightAnswers = $RightAnswers . '1';
    }else{
      $RightAnswers = $RightAnswers . '0';
    }
    if(isset($_POST['option4Checkbox'])){
      $RightAnswers = $RightAnswers . '1';
    }else{
      $RightAnswers = $RightAnswers . '0';
    }
    $Option1 = $_POST['Option1'];
    $Option2 = $_POST['Option2'];
    $Option3 = $_POST['Option3'];
    $Option4 = $_POST['Option4'];

    $Course = $_POST['Course'];
    $select1 = mysqli_query($connection, "SELECT * FROM course WHERE name = '$Course'");
    $row1 = $select1->fetch_assoc();
    $idOfCourse = $row1['id'];
    $idOfProf = $row1['professorId'];

    if(isset($_POST['Radio'])){
      $Difficulty = $_POST['Radio'];
    }
    $Concept = $_POST['Concept'];
    $Reference = $_POST['Reference'];
    if(isset($_POST["Course"])){
    if($Text == '' || $Explanation == '' || ($Option1 == '' && $Option2 == '' && $Option3 == '' && $Option4 == '')){
      echo "<p id='Error'> Please fill out all of the required fields! </p>";
    }else{
      if($id == $idOfProf){
        $sql = "INSERT INTO question (creatorId,approved,image,text,answer1,answer2,answer3,answer4,correctAnswers,explanation,difficulty,courseId) VALUES ('$id',1,'$ImageLink','$Text','$Option1','$Option2','$Option3','$Option4',$RightAnswers,'$Explanation',$Difficulty,$idOfCourse)";
        $connection->query($sql);
      }else{
        $sql = "INSERT INTO question (creatorId,approved,image,text,answer1,answer2,answer3,answer4,correctAnswers,explanation,difficulty,courseId) VALUES ('$id',0,'$ImageLink','$Text','$Option1','$Option2','$Option3','$Option4',$RightAnswers,'$Explanation',$Difficulty,$idOfCourse)";
        $connection->query($sql);
      }
      $select = mysqli_query($connection, "SELECT * FROM question ORDER BY id DESC LIMIT 1");
      $row = $select->fetch_assoc();
      $questionId = $row['id'];

      if($Reference != ''){
      $References = explode(",",$Reference);
      foreach($References as $ref){
        $ref = trim($ref);
        $sql = "INSERT INTO webreference (questionId, weblink) VALUES ($questionId, '$ref')";
        $connection->query($sql);
      }
    }
    if($Concept != ''){
      $Concepts = explode(",",$Concept);
      foreach($Concepts as $conc){
        $conc = trim($conc);

        $Concept2 = explode(":",$conc);
        $cName = $Concept2[1];
        $cCourse = $Concept2[0];

        $select1 = mysqli_query($connection, "SELECT * FROM course WHERE name = '$cCourse'");
        $row1 = $select1->fetch_assoc();
        $courseId = $row1['id'];

        $sql = "INSERT INTO concept (name, questionId, courseId) VALUES ('$cName',$questionId,$courseId)";
        $connection->query($sql);
      }
    }
    if($id != $idOfProf){
      $sql = "INSERT INTO notification (userId,name,fromUser,description,type,questionId) VALUES ($idOfCourse, 'A Question Awaits your Approval!', $id, 'The user with the id $id has submitted a question for approval.', 0, '$questionId')";
      $connection->query($sql);
    }
      echo "<p id='Success'> Question Added </p>";
    }
  } else {echo "<p id='Error'> Please fill out all of the required fields! </p>";}
}
  ?>
</body>
</html>
