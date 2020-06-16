<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
    .entries{
  border-style: solid;
}
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <title>My Forum</title>
</head>
<body>
  <?php session_start();?>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>
  <p>
    <h1>Posts</h1>
    <p id="posts">
    <!-- The following php should not be here, I just added it so you can see that the records
    print out correctly. I could not make the redirection from displaypost through ajax work -->
    <?php 
      $query = "select title,creator,content from post";
      $result = mysqli_query($conn, $query);

      if(mysqli_num_rows($result)>0){

        while($data = mysqli_fetch_row($result)){
          print "<div class='entries'>";
          print "<b>Title: $data[0] </b><br />";
          // We get the username if the creator
          $query2 = "select username from user where id = $data[1]";
          $result2 = mysqli_query($conn, $query2);
          $row = mysqli_fetch_row($result2);
          print "By: $row[0] <br />";
          print "Content: <br/>".$data[2]."</div><br/><br/>";  
        }

      } else {
        print "<p> No posts available </p>";
      } 
    ?> 

    </p>
  
  <?php
    // check if $_SESSION['username'] is declared
    // if it is, then the user is logged in
    if (isset($_SESSION['username'])) {
      $user = $_SESSION['username'];

      print "<h3> Create a post as $user </h3>";
      // to test the ajax function, please delete the action tag: 
      print "<form name='post_submission' method='post' action='writepost.php' onsubmit='return check()'>
        Title <input type='text' name='title' /> <br />
        Content <br />
        <textarea name='content'/> </textarea> <br />
        <input type='submit' value='Submit post'/>
      </form>";

    }
    else {
      print "You need to log in to make a post.";
    }
  ?>
  </p>
  <script>
    function check(){
      var title = document.post_submission.title.value;
      var content = document.forms.post_submission.content.value;
      if (title == ""){
        alert("Please input a title.");
        return false;
      } else if(content == ""){
        alert("Please input some content.");
        return false;
      }
        //uncomment this to see how the ajax function works
        //ajax_on_submit();
        return true;   
    }

    function displayPost(){
      var html = '';
      for(var i in data){
        html += data[i]+'<br/>';
      }
      $('#posts').html(html)
    }

    function ajax_on_submit(){
      $.ajax({
        method: "POST",
        url: "writepost.php",
        data: {title: $("#title").val(), content: $("#content").val()}
      })
      .done(function(response){
        displayPost(response);
      })
      .fail(function(jqXHR){
        alert("Something went wrong");
      })
      .always(function(){
      })
      return false;
    }

  </script>

</body>
</html>



