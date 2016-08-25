<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Blog</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1>Blog</h1>
<?php
include 'lib/Parsedown.php';
include 'lib/Spyc.php';

$dir    = 'post';
$files = array_diff(scandir($dir), array('..', '.'));

$posts = array();

function to_slug($string){
  return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

foreach($files as $post){
  $filename = "post/$post";
  $handle = fopen($filename, "r");
  $contents = fread($handle, filesize($filename));
  fclose($handle);

  $subject = $contents;
  $pattern = '/^---\n([\s\S]+)\n---\n/';
  preg_match($pattern, $subject, $matches);

  $postMeta = spyc_load($matches[1]);

  $post = $postMeta;

  $postBody =  preg_replace('/^---\n[\s\S]+\n---\n/', '', $contents);

  $post['slug'] = to_slug($post['title']);

  $post['body'] = $postBody;

  array_push($posts, $post);

}

function date_compare($a, $b)
{
    $t1 = strtotime($a['datetime']);
    $t2 = strtotime($b['datetime']);
    return $t2 - $t1;
}
usort($posts, 'date_compare');

foreach($posts as $post){
  if($post['slug'] == $_GET['slug']){
    echo("    <h2>".$post['title']."</h2>\n");
    echo("    <p>".$post['author']." - ".$post['datetime']."</p>\n");
    $Parsedown = new Parsedown();
    echo($Parsedown->text($post['body']));
  }
}

?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
  </body>
</html>
