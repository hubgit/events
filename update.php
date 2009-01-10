<?php
  
require_once dirname(__FILE__) . '/includes/main.inc.php';

chdir(realpath(dirname(__FILE__)));
  
$files = scandir(dirname(__FILE__) . '/parsers');
foreach ($files as $file){
  if (strstr($file, '.'))
    continue;
    
  $base = basename($file);
  
  system(sprintf("'parsers/%s' > 'output/%s.ics'", $file, $base));
}
