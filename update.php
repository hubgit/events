<?php
  
require_once 'includes/main.inc.php';

chdir(realpath(dirname(__FILE__)));
  
$files = scandir(dirname(__FILE__) . '/parsers');
foreach ($files as $file){
  if (strpos($file, '.') === 0)
    continue;
    
  $base = basename($file);
  
  system(sprintf("'parsers/%s' > 'output/%s.ics'", $file, $base));
}
