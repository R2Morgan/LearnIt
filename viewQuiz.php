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

$quizId = $_GET['quiz'];
 ?>

 <html>
 <head>
   <title> Learn It | View Quiz </title>
   <link rel="stylesheet" href="css/viewQuiz.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 </head>
 <body>
   <button id="Back"> < Go Back </button>
   <script>
   $('#Back').click(function(){
       window.location.href='myQuizzes.php';
   })
   </script>
     <ul id="QuizDiv">
       <?php
           $id = $_SESSION['userId'];
           $sql = mysqli_query($connection, "SELECT DISTINCT image, text, answer1, answer2, answer3, answer4, correctAnswers, explanation, difficulty FROM question INNER JOIN hasquestion ON hasquestion.questionId = question.id WHERE quizId =$quizId");
           if($result2->num_rows > 0)
           {while ($row = $sql->fetch_assoc()){
       ?>
       <li>
         <table id="QuestionTable">
         <tr id="Question">
           <td id="Text"><?php echo $row['text']; ?>
           </td>
           <td id="ImageTD">
             <?php $image = $row['image']; if($image != ''){?>
           <img id="Image" src="<?php echo $row['image']; ?>" alt="Question Image">
         </img>
       <?php } else { ?>
         <h3 id="Nothing"></h3> <?php } ?>
       </td>

         <?php $difficulty = $row['difficulty'];
         if ($difficulty == 1){
           $difficulty = 'Easy';
         } else if($difficulty == 2){
           $difficulty = 'Medium';
         } else if($difficulty == 3){
           $difficulty = 'Hard';
         } ?>
         <td id="Explanation">
            <?php $explanation = $row['explanation']; echo $explanation;?>
         </td>
         <td id=<?php echo $difficulty; ?>> <?php echo $difficulty; ?>
         </td>
       </tr>
       <tr id="Answers">
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
       </tr>
       </table>
       </li>
       <?php
     }}
       ?>
 </ul>
 </body>
 </html>
