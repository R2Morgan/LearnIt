<?php
include 'connect.php';
 ?>

 <html>
 <head>
   <title> Learn It | Register </title>
   <link rel="stylesheet" href="css/register.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
   <form method="post" id="LoginForm">
     <br>
     <label for="Id" id="idLabel">Identification</label>
     <label for="Password" id="passwordLabel">Password</label>
     <br>
     <input type="text" id="Id" name="Id" placeholder="ab1234">
     <input id="Password" name="Password" type="password" placeholder="•••••••••••">
     <br>
     <label for="FirstName" id="firstNameLabel">First Name</label>
     <label for="City" id="cityLabel">City</label>
     <br>
     <input id="FirstName" name="FirstName" type="text" placeholder="John">
     <input type="text" id="City" name="City" placeholder="Cluj - Napoca">
     <br>
     <label for="LastName" id="lastNameLabel">Last Name</label>
     <label for="Faculty" id="facultyLabel">Faculty</label>
     <br>
     <input type="text" id="LastName" name="LastName" placeholder="Smith">
        <select id="Faculty" name="Faculty">
          <option value="" disabled selected>Select your Faculty</option>
          <?php
              $sql = mysqli_query($connection, "SELECT name FROM faculty");
              while ($row = $sql->fetch_assoc()){
          ?>
          <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
          <?php
              }
          ?>
        </select>
     <br>
     <input type="submit" id="Register" name="Register" value="Register">
     <?php
     if(isset($_POST['Register'])) {

     session_start();

     $userId = mysqli_real_escape_string($connection, $_POST['Id']);
     $first = mysqli_real_escape_string($connection, $_POST['FirstName']);
     $last = mysqli_real_escape_string($connection, $_POST['LastName']);
     $city = mysqli_real_escape_string($connection, $_POST['City']);
     $pass = mysqli_real_escape_string($connection, $_POST['Password']);

     $selectQuery = $connection -> prepare("SELECT * FROM user WHERE id= ?");
     $selectQuery -> bind_param("s", $userId);
     $selectQuery -> execute();
     $result= $selectQuery -> get_result();
     $selectQuery->close();
     $faculty='';
     if(isset($_POST["Faculty"]))
     {$faculty = $_POST['Faculty'];}
     if ($result->num_rows == 1){
        echo "<p id='error1'> Oops! Looks like this Id is already in use! </p>";
     }
     else{
       if($userId != '' && $first != '' && $last != '' && $city != '' && $pass != ''){
          if(strlen($first) < 2){
            echo "<p id='error1'> Please choose a real first name! </p>";
          }else if (strlen($last) < 2){
            echo "<p id='error1'> Please choose a real last name! </p>";
          }else if (strlen($pass) < 5){
            echo "<p id='error1'> Please choose a better password. Must be at least 5 characters long! </p>";
          }else if ($faculty == ''){
            echo "<p id='error1'> Please choose a Faculty! </p>";
          }else if (strlen($city) < 3){
            echo "<p id='error1'> Please choose a real city! </p>";
          } else {
            $selectQuery = $connection -> prepare("SELECT * FROM faculty WHERE name = ? LIMIT 1");
            $selectQuery -> bind_param("s", $faculty);
            $selectQuery -> execute();
            $result= $selectQuery -> get_result();
            $selectQuery->close();
            $row = mysqli_fetch_row($result);
            $row = $row[0];
            $sql = "INSERT INTO user (id,first_name,last_name,password,city,faculty,isProfessor) VALUES ('$userId', '$first', '$last', '$pass', '$city', '$row', 0)";
            $connection->query($sql);

            echo "<p id='success'> User added successfully! </p>";
          }
        }else {
            echo "<p id='error1'> Please fill out all the information! </p>";
          }
     }
}
     ?>
   </form>
 </div>
 </body>
