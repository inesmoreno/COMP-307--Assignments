<body>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>

  <?php

    try{

     $stmt = $conn->prepare("insert into user (username, password) values (?, ?)");

     $stmt->bind_param("ss", 
      $username,
      $password
    );

     if(empty($_POST['username'])){
        throw new Exception( 'You must enter a username');
     } else {
      $username = $_POST['username'];
     }
     if(empty($_POST['password'])){
      throw new Exception( 'You must enter a password');
     } else {
      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
     }

    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $success = $stmt->execute();

    if(!$success){
      throw new Exception ($stmt-> error);
    }

    print "<p>Signup successful</p>";

  } catch (Exception $e) {
    print "<p> Signup failed: ".$e->getMessage()."</p>";
    print "</b> Please try again.";
  }

  ?>
</body>