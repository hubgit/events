<?php
  
require_once __DIR__ . '/includes/main.inc.php';

chdir(realpath(__DIR__));
  
$files = scandir(__DIR__ . '/parsers');
foreach ($files as $file){
  if (strstr($file, '.'))
    continue;
    
  $base = basename($file);
  
  system(sprintf("'parsers/%s' > 'output/%s.ics'", $file, $base));
}
