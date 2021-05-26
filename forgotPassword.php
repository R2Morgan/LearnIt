<?php
include 'connect.php';
 ?>

 <html>
 <head>
   <title> Learn It | Forgot Password </title>
   <link rel="stylesheet" href="css/forgotPassword.css">
 </head>
 <body>
   <div id="Box">
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
   <form method='post' id="ForgotPassword">
     <br>
     <label id="emailLabel">Your Email Address</label>
     <input type="text" id="Email" name="Email" placeholder="john_smith@something.com">
     <br>
     <label id="identificationLabel">Identification</label>
     <input id="Identification" name="Identification" type="text" placeholder="ab1234">
     <br>
     <input type="submit" id="RecoverPassword" name="RecoverPassword" value="Recover Password">
   </form>
 <?php
 if(isset($_POST['RecoverPassword'])) {

 session_start();

 $email = $_POST['Email'];
 $userId = $_POST['Identification'];

 $selectQuery = $connection -> prepare("SELECT * FROM user WHERE id= ? LIMIT 1");
 $selectQuery -> bind_param("s", $userId);
 $selectQuery -> execute();
 $result= $selectQuery -> get_result();
 $selectQuery->close();
 if($userId == '' || $email == '')
 {
   echo "<p id='error'> Please fill out all the boxes </p>";
 }
 else if ($result->num_rows == 1){
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
     echo "<p id='error'> Please choose a valid email Address </p>";
   }else
        $to_email = $email;
        $subject = "Password Recovery - LearnIt";
        $password = md5($email);
        $body = "Your Temporary Password is: $password. You may change it on your next visit to our website!";

          if (mail($to_email, $subject, $body)) {
            echo "<p id='success'> Email successfully sent to $to_email... </p>";
            $insertQuery = $connection -> prepare("UPDATE user SET password=? WHERE id=?");
            $insertQuery -> bind_param("ss", $password, $userId);
            $insertQuery -> execute();
          } else {
            echo "<p id='fail'> Email sending failed... </p>";
          }
  }else { echo "<p id='error'> Invalid Identification </p>"; }
}
 ?>
</div>
 </body>
 </html>
