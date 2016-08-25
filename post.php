<?php $workingDir = __DIR__; ?>

<?php require_once "$workingDir/resources/templates/header.php"; ?>

<?php
  require_once "$workingDir/resources/library/markblog.php";
  # Get all posts and print them out in HTML
  foreach(sortPosts(getPosts(getFiles("$workingDir/resources/posts/"), "$workingDir/resources/posts/")) as $post){
    if($post['slug'] == $_GET['slug']){
      echo('<h2>'.$post['title']."</h2>\n");
      echo("<p>".$post['author']." - ".$post['datetime']."</p>\n");
      echo(convertMarkdown($post['body']));
    }
  }
?>

<?php require_once "$workingDir/resources/templates/footer.php"; ?>
