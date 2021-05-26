<?php
include 'connect.php';
 ?>

 <html>
 <head>
   <title> Learn It | Contact Us </title>
   <link rel="stylesheet" href="css/contact.css">
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
    <h3 id="Title"> Contact Us </h3>
    <p id="Title2"> Write us a message with any questions or remarks you might have </p>
   <form method='post' id="ContactForm">
     <br>
     <label for="Id" id="idLabel">Identification</label>
     <label for="Mesage" id="messageLabel">Message</label>
     <br>
     <input type="text" id="Id" name="Id" placeholder="ab1234">
     <textarea id="Message" name="Message" placeholder="I love this website..."></textarea>
     <br>
     <label for="Email" id="emailLabel">Email</label>
     <br>
     <input id="Email" name="Email" type="text" placeholder="john_smith@something.com">
     <br>
     <input type="submit" id="Send" name="Send" value="Send">
   </form>
 <?php
 if(isset($_POST['Send'])) {

 session_start();

 $userId = $_POST['Id'];
 $email = $_POST['Email'];
 $description = $_POST['Message'];

 $selectQuery = $connection -> prepare("SELECT * FROM user WHERE id= ? LIMIT 1");
 $selectQuery -> bind_param("s", $userId);
 $selectQuery -> execute();
 $result= $selectQuery -> get_result();
 if($userId == "" || $email == "" || $description == "")
 {
   echo "<p id='error'> Please fill out all the fields! </p>";
 }
 else if ($result->num_rows == 1){
 	  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      echo "<p id='error'> Invalid Email Address! </p>";
    }
    else if(strlen($description) < 5)
    {
      echo "<p id='error'> Your Message is too short! </p>";
    }
    else{
      $to_email = "stef12121999@gmail.com";
      $subject = "Message from $userId @ $email";
      $body = "$description";

        if (mail($to_email, $subject, $body)) {
          echo "<p id='success'> Email successfully sent!</p>";
        } else {
          echo "<p id='fail'> Email sending failed... </p>";
        }
    }
  }else { echo "<p id='error'> No such user has been found! </p>"; }
}
 ?>
</div>
 </body>
 </html>
