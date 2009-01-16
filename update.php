<?php
  
chdir(realpath(__DIR__));

require 'main.inc.php';
  
$files = scandir('parsers');
foreach ($files as $file){
  $file = 'parsers/' . $file;
  if (is_dir($file))
    continue;
  
  if (substr(decoct(fileperms($file)), 3) != 755) // executable
    continue;
    
  print "$file\n";
  
  system(sprintf("'%s' > 'output/%s.ics'", $file, pathinfo($file, PATHINFO_FILENAME)));
}
