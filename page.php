<?php $workingDir = __DIR__; ?>

<?php require_once "$workingDir/resources/templates/header.php"; ?>

<?php
  require_once "$workingDir/resources/library/markblog.php";
  # Get all posts and print them out in HTML
  foreach(sortPosts(getPosts(getFiles("$workingDir/resources/pages/"), "$workingDir/resources/pages/")) as $post){
    if($post['slug'] == $_GET['slug']){
      echo('<ol class="breadcrumb">'."\n");
      echo('<li class="breadcrumb-item"><a href="/">Start</a></li>'."\n");
      echo('<li class="breadcrumb-item active">'.$post['title']."</li>\n");
      echo("</ol>\n");
      echo(convertMarkdown($post['body']));
    }
  }
?>

<?php require_once "$workingDir/resources/templates/footer.php"; ?>
