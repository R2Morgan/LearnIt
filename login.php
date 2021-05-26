<?php
include 'connect.php';
 ?>

 <html>
 <head>
   <title> Learn It | Log In </title>
   <link rel="stylesheet" href="css/login.css">
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
   <form method='post' id="LoginForm">
     <br>
     <label for="Id" id="idLabel">Identification</label>
     <br>
     <input type="text" id="Id" name="Id" placeholder="ab1234">
     <br>
     <label for="Password" id="passwordLabel">Password</label>
     <br>
     <input id="Password" name="Password" type="password" placeholder="•••••••••••">
     <br>
     <input type="submit" id="Login" name="Login" value="Log In">
     <br>
     <a href="forgotPassword.php" id="Register"> Forgot Your Password? </a>
   </form>
 <?php
 if(isset($_POST['Login'])) {

 session_start();

 $userId = $_POST['Id'];
 $pass = $_POST['Password'];

 $selectQuery = $connection -> prepare("SELECT * FROM user WHERE id= ? AND password= ? LIMIT 1");
 $selectQuery -> bind_param("ss", $userId, $pass);
 $selectQuery -> execute();
 $result= $selectQuery -> get_result();
 $isProfessor = mysqli_fetch_row($result);
 $isProfessor = $isProfessor[6];
 if ($result->num_rows == 1){
    $_SESSION['userId'] = $userId;
    if($isProfessor == '1'){
    header("location:professor_main.php");
    }elseif($isProfessor == '0'){
 	  header("location:user_main.php");
  }
    }else { echo "<p id='error'> Something is wrong here... Perhaps it's the password... Or maybe the username? </p>"; }
}
 ?>
</div>
 </body>
 </html>
