<?php session_start();?>
<body>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>

  <?php
    // First, we get the user id corresponding to the creator
    $stmt1 = $conn-> prepare("select id from user where username=?");

    $stmt1->bind_param("s", $username);
    $username = $_SESSION['username'];

    $stmt1->execute();

    $stmt1->bind_result($user_id);
    $stmt1->fetch();

    $stmt1->close();

    //Then we insert the post into the table. 
    $stmt2 = $conn->prepare("insert into post (creator, title, content) values (?, ?, ?)");
    $stmt2->bind_param("iss", 
      $user_id,
      $title,
      $content
    );

    $title = $_POST['title'];
    $content = $_POST['content'];

    $success = $stmt2->execute();

  ?>
</body>