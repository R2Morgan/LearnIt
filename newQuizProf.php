<?php
include 'connect.php';
session_start();
if( ! $_SESSION['userId']){header('location:index.php');}
$id = $_SESSION['userId'];
$sql = mysqli_query($connection, "SELECT * FROM user WHERE id = '$id'");
$result = $sql->fetch_assoc();
$selectQuery = $connection -> prepare("SELECT * FROM question");
$selectQuery -> execute();
$result2 = $selectQuery->get_result();
$all = $result2->num_rows;
$selectQuery1 = $connection -> prepare("SELECT * FROM question WHERE approved = 0");
$selectQuery1 -> execute();
$result1 = $selectQuery1->get_result();
$approved = $result1->num_rows;
$courseId = "";
if(isset($_GET['course'])){
  $courseId = $_GET['course'];
}
?>

<html>
<head>
  <title> Learn It | New Quiz </title>
  <link rel="stylesheet" href="css/newQuizProf.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <button id="Back"><image alt="Back" id="BackImage" src="Art/back.png"></image></button>
    <script>
    $(document).ready(function(){
      $("#Back").click(function(){
        location.replace("professor_q.php");
      });
    });
    </script>
    <div id="Screen">
      <form id="NewQuizForm" method="post">
      <select id="Course" name="Course" onchange="this.form.submit()">
        <option value="" disabled selected>Select the Course</option>
        <?php
            $sql = mysqli_query($connection, "SELECT * FROM course WHERE professorId = $id");
            while ($row = $sql->fetch_assoc()){
        ?>
        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $courseId){echo 'selected';} ?>><?php echo $row['name']; ?></option>
        <?php
            }
        ?>
        <?php
        if(isset($_POST['Course'])){
          echo "HEY";
          $courseId = $_POST['Course'];
          $url = 'location:newQuizProf.php?course='.$courseId;
          header($url);
        }
        ?>
      </select>
      <br>
      <input type="text" id="Name" name="Name" placeholder="Quiz Name"></input>
      <br>
      <input type="textarea" id="Description" name="Description" placeholder="The description of the quiz"></input>
      <input type="checkbox" id="isExam" name="isExam" title="Check if you want this quiz to be the final Exam"></input>
      <br>
      <div id="QuestionDiv">
        <ul id="List">
          <?php
              $id = $_SESSION['userId'];
              $sql = mysqli_query($connection, "SELECT * FROM question WHERE courseId = $courseId AND approved=1");
              if($result2->num_rows > 0 && $courseId != "")
              {while ($row = $sql->fetch_assoc()){
                $questionId = $row['id'];
          ?>
          <li>
            <table id="QuestionTable">
            <tr id="Question">
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
            <td id="CheckboxTab">
              <input type="checkbox" id="Check" value="<?php echo $questionId; $checkboxName = 'Checkbox'.$questionId; ?>" name="<?php echo $checkboxName; ?>"></input>
              <?php
              if(isset($_POST["Submit"])){
                if($_POST["Name"] == ""){
                  echo '<p id="Error"> You must specify a name for your Quiz </p>';
                }else{
                  $quizName = $_POST["Name"];
                  $quizDescription = $_POST["Description"];
                  if(isset($_POST['isExam'])){
                    $sql1 = mysqli_query($connection, "INSERT INTO quiz(creatorId, courseId, name, type, description) VALUES ('$id', $courseId, '$quizName', 3, '$quizDescription')");
                  }else{
                    $sql1 = mysqli_query($connection, "INSERT INTO quiz(creatorId, courseId, name, type, description) VALUES ('$id', $courseId, '$quizName', 2, '$quizDescription')");
                  }
                }
                $sql2 = mysqli_query($connection, "SELECT * FROM quiz ORDER BY id DESC LIMIT 1");
                $row2 = $sql2->fetch_assoc();
                $quizId = $row2['id'];

                $checkboxName = "'".$checkboxName."'";
                if(isset($_POST["Checkbox".$questionId]) && $_POST["Name"] != ""){
                  echo $questionId; echo $quizId;
                  $sql = mysqli_query($connection, "INSERT INTO hasquestion(questionId,quizId) VALUES ($questionId, $quizId)");
                }
              }

               ?>
            </td>
          </tr>
          </table>
          </li>
          <?php
        }}
          ?>
    </ul>
      </div>
      <br>
      <input type="Submit" id="Submit" name="Submit" value="Create Course"></input>
    </form>
    </div>
</body>
</html>
