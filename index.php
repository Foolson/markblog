<?php $workingDir = __DIR__; ?>

<?php require_once "$workingDir/resources/templates/header.php"; ?>

<?php
  require_once "$workingDir/resources/library/markblog.php";
  # Get all posts and print them out in HTML
  foreach(sortPosts(getPosts(getFiles("$workingDir/resources/posts/"), "$workingDir/resources/posts/")) as $post){
    echo('<a href="post.php?slug='.$post['slug'].'"><h2>'.$post['title']."</h2></a>\n");
    echo('<p class="text-muted"><em>'.$post['author']." - ".$post['datetime']."</em></p>\n");
    echo(convertMarkdown($post['body']));
  }
?>

<?php require_once "$workingDir/resources/templates/footer.php"; ?>
