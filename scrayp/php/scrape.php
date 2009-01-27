#!/usr/bin/env php
<?php

//require dirname(__FILE__) . '/../../main.inc.php';
$dir = dirname(__FILE__) . '/../definitions';

$options  = array(
  'h' => 'help',
  'f:' => 'file:',
  'u:' => 'url:',
  'p:' => 'page:',
);

$opt = getopt(implode('', array_keys($options))); //, array_values($options));

foreach ($options as $short => $long)
  if (!isset($opt[$long]) && isset($opt[$short]))
    $opt[$long] = $opt[$short];

//var_dump($opt);
if (isset($opt['help']))
  exit(usage());

$files = $opt['f'] ? array($opt['f']) : scandir($dir);

foreach ($files as $file){
  if (!preg_match('/\.js$/', $file))
    continue;
    
  //debug($file);
  
  $json = file_get_contents($dir . '/' . $file);
  $json = str_replace('\\', '\\\\', $json); // for regular expressions

  $defs = json_decode($json);
  if (!is_object($defs))
    die("Error parsing JSON definitions:" . json_last_error());
    
 
  if (isset($opt['u']))
    $defs->url = $opt['u'];

  //debug($defs);
  
  //$scrayp = 'http://0.0.0.0:10000?' . http_build_query($params); // does urlencode instead of rawurlencode, so adds plusses
  $scrayp = sprintf('http://0.0.0.0:10000?defs=%s', rawurlencode(json_encode($defs)));
  //debug($scrayp);

  $json = file_get_contents($scrayp);
  //debug($json);
  print $json; exit();
  
  $items = json_decode($json);

  //debug($items);

  if (!is_object($items))
    die("Error parsing JSON response:" . json_last_error());
}

function usage(){
  print <<<END
  -h --help show this help
  -f --file definitions file
  -u --url replacement url
  -p --page page number
END;
}

function debug($text){
  $debug = 0;
  if ($debug){
    print_r($text);
    print "\n";
  }
}

  
