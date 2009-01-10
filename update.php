<?php

require_once __DIR__ . '/includes/main.inc.php';

chdir(realpath(__DIR__));
  
$files = scandir('parsers');
foreach ($files as $file){
  if (strpos($file, '.') === 0)
    continue;
    
  $base = basename($file);
  
  system(sprintf("'parsers/%s' > 'output/%s.ics'", $file, $base));
}
