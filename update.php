<?php

$dir = dirname(__FILE__);
  
require_once $dir . '/includes/main.inc.php';

chdir(realpath($dir));
  
$files = scandir($dir . '/parsers');
foreach ($files as $file){
  if (strstr($file, '.'))
    continue;
    
  $base = basename($file);
  
  print "$base\n";
  
  system(sprintf("'parsers/%s' > 'output/%s.ics'", $file, $base));
}
