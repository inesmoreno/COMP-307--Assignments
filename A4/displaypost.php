<?php session_start();?>
<body>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>

    <?php
    $query = "select title,creator,content from post";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result)>0){
	$query = "select title,creator,content from post";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result)>0){

      $records = array();

      while($data = mysqli_fetch_row($result)){
        array_push($records, $row['title']);
        // We get the username if the creator
        $query2 = "select username from user where id = $data[1]";
        $result2 = mysqli_query($conn, $query2);
        $row = mysqli_fetch_row($result2);
        array_push($records, $row[0]);
        array_push($records, $row['content']);
      }
      print(json_encode($records));


    } else {
      print "<p> No posts available </p>";

    } 
  ?>
</body>


    
    