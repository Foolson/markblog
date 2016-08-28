<?php
require_once 'Parsedown.php';
require_once 'Spyc.php';

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
    $filename = "$folder".'/'."$post";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $subject = $contents;
    $pattern = '/---\n((?:.+: {1,}.+\n)+)---\n/';
    preg_match($pattern, $subject, $matches);
    $postMeta = spyc_load($matches[1]);
    $post = $postMeta;
    $postBody =  preg_replace('/---\n(?:.+: {1,}.+\n)+---\n/', '', $contents);
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
?>
