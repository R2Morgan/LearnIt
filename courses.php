<?php
include 'connect.php';
 ?>

<html>
<head>
  <title> Learn It | Courses </title>
  <link rel="stylesheet" href="css/courses.css">
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
<ul id="List">
      <?php
          $sql = mysqli_query($connection, "SELECT * FROM course");
          while ($row = $sql->fetch_assoc()){
            $id = $row['professorId'];
            $sql1 = mysqli_query($connection, "SELECT * FROM user WHERE id = $id");
            $row1 = $sql1->fetch_assoc();
      ?>
      <li>
        <div id="Course">
          <h3 id="Title"><?php echo $row['name']; ?>
          </h3>
          <h3 id="Professor"><?php echo 'Professor', ' ' , $row1['first_name'], ' ', $row1['last_name']; ?>
          </h3>
          <h3 id="Description"><?php echo $row['description'] ?>
          </h3>
          <img alt="Course Image" src="<?php if($row['image'] == ''){echo 'Art/No_Picture.png';}else{echo $row['image'];} ?>"></img>
        </div>
      </li>
      <?php
          }
      ?>
</ul>
</div>
</body>
</html>
