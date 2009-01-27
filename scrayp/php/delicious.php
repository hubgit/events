<?php

$url = md5('http://simile.mit.edu/wiki/Crowbar');

$items = array();
$page = 0;
do {
  $page++;
  $json = system(sprintf("php scrape.php -fdelicious.js -u 'http://delicious.com/url/%s?show=all&page=%d'", $url, $page));
  $data = json_decode($json);
  print_r($data);
  sleep(1);
  
  foreach ($data as $post){
    $items[$post->{'dc:identifier'}] = $post;
  }
} while (!empty($data));


file_put_contents("output/$url.js", json_encode($items));
