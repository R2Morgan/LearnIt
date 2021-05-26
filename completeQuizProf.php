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

$quizId = $_GET['quiz'];
$totalPoints = 0;
$totalQuestions = 0;
 ?>

 <html>
 <head>
   <title> Learn It | Complete Quiz </title>
   <link rel="stylesheet" href="css/completeQuizProf.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 </head>
 <body>
   <button id="Back"> < Go Back </button>
   <script>
   $('#Back').click(function(){
       window.location.href='professor_q.php';
   })
   </script>
     <ul id="QuizDiv">
       <form method="POST">
       <?php
           $id = $_SESSION['userId'];
           $sql = mysqli_query($connection, "SELECT DISTINCT id, image, text, answer1, answer2, answer3, answer4, correctAnswers, explanation, difficulty FROM question INNER JOIN hasquestion ON hasquestion.questionId = question.id WHERE quizId =$quizId");
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
       <tr id="GivenAnswers">
         <td>
           <input type="checkbox" id="checkbox1" name="<?php $questionId = $row['id']; echo "checkbox"; echo $questionId; echo "1";?>">
         </td>
         <td>
           <input type="checkbox" id="checkbox2" name="<?php $questionId = $row['id']; echo "checkbox"; echo $questionId; echo "2";?>">
         </td>
         <td>
           <input type="checkbox" id="checkbox3" name="<?php $questionId = $row['id']; echo "checkbox"; echo $questionId; echo "3";?>">
         </td>
         <td>
           <input type="checkbox" id="checkbox4" name="<?php $questionId = $row['id']; echo "checkbox"; echo $questionId; echo "4";?>">
         </td>
       </tr>
       </table>
       </li>
     <?php
     if(isset($_POST['button'])){
       $answerSheet;
       if(isset($_POST['checkbox'.$questionId.'1'])){
         $answerSheet = '1';
       }else{$answerSheet = '0';}
       if(isset($_POST['checkbox'.$questionId.'2'])){
         $answerSheet = $answerSheet.'1';
       }else{$answerSheet = $answerSheet.'0';}
       if(isset($_POST['checkbox'.$questionId.'3'])){
         $answerSheet = $answerSheet.'1';
       }else{$answerSheet = $answerSheet.'0';}
       if(isset($_POST['checkbox'.$questionId.'4'])){
         $answerSheet = $answerSheet.'1';
       }else{$answerSheet = $answerSheet.'0';}
       if($answerSheet == $correctAnswers){
         $totalPoints = $totalPoints + 1;
         $totalQuestions = $totalQuestions + 1;
       }else{$totalQuestions = $totalQuestions + 1;}

       $grade = ($totalPoints / $totalQuestions) * 10;

       $sqll = mysqli_query($connection, "INSERT INTO takequiz(userId, quizId, grade) VALUES ('$id', $quizId, $grade)");
       header('location:viewQuizProf.php?quiz='.$quizId);
     }
   }}
     ?>
 </ul>
 <input type="submit" name="button" id="button"></input>
</form>
 </body>
 </html>
