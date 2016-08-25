<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1>Blog</h1>
<?php
include 'lib/Parsedown.php';
include 'lib/Spyc.php';

# Function to get all files in directory and ignore '..' and '.'
function getFiles($dir){
  return array_diff(scandir($dir), array('..', '.'));
}

# Function to convert post title to a slug
function toSlug($string){
  return strtolower(trim(preg_replace('/[^A-Za-z0-9-åäöÅÄÖ]+/', '-', $string)));
}

# Function to gather all posts with their metadata
function getPosts($files, $folder){
  $posts = array();
  foreach($files as $post){
    $filename = "$folder/$post";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $subject = $contents;
    $pattern = '/^---\n([\s\S]+)\n---\n/';
    preg_match($pattern, $subject, $matches);
    $postMeta = spyc_load($matches[1]);
    $post = $postMeta;
    $postBody =  preg_replace('/^---\n[\s\S]+\n---\n/', '', $contents);
    $post['slug'] = toSlug($post['title']);
    $post['body'] = $postBody;
    array_push($posts, $post);
  }
  return $posts;
}

# Function to sort all posts after datetime
function sortPosts($posts) {
  function date_compare($a, $b)
  {
      $t1 = strtotime($a['datetime']);
      $t2 = strtotime($b['datetime']);
      return $t2 - $t1;
  }
  usort($posts, 'date_compare');
  return $posts;
}

# Function to convert markdown to HTML
function convertMarkdown($body){
  $Parsedown = new Parsedown();
  return $Parsedown->text($body);
}

# Get all posts and print them out in HTML
foreach(sortPosts(getPosts(getFiles('post'), 'post/')) as $post){
  echo('   <a href="post.php?slug='.$post['slug'].'"><h2>'.$post['title']."</h2></a>\n");
  echo("    <p>".$post['author']." - ".$post['datetime']."</p>\n");
  echo(convertMarkdown($post['body']));
}
?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
  </body>
</html>
