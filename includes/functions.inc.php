<?php

function url_html_xml($url){
  global $root;
  $root = dirname($url);
  
  $dom = @DOMDocument::loadHTMLFile($url);
  //$root = $dom->baseURI; // TODO: check for BASE element in HTML?
  return simplexml_import_dom($dom);
}

function first($array){
  return current(array_slice($array, 0, 1));
}

function sanical($text){
  $text = filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
  $text = preg_replace('/&.+?;/', ' ', $text);
  $text = str_replace(array("\n", ',', ';'), array('\\n', '\,', '\;'), $text);
  return trim($text) . "\n";
}

function clean_html($html){
  $html = preg_replace('/<!--[^>]*-->/', '', $html);
  return $html;
}

function innerXML($node){
  $output = array();

  foreach ($node->children() as $child)
    $output[] = $child->asXML();
    
  return implode(' ', $output);
}

function make_link($url){
  global $root;
  $url = (string) $url;
  if (!preg_match('!^\w+://!i', $url)){
    if (!preg_match('!^/!', $url))
      $url = '/' . $url;
      
    $url = $root . $url;
  }
  
  return $url;
}

function ical_start($name){
  print sprintf("BEGIN:VCALENDAR\nVERSION:2.0\nX-WR-TIMEZONE:Europe/London\nX-WR-CALNAME:%s", sanical($name));
}

function ical_event($event){
  require __DIR__ . '/event.tpl.php';
}

function ical_end(){
  print "END:VCALENDAR\n";
}

function debug($t){
  $debug = 0;
  if ($debug){
    print_r($t);
    print "\n";
  }
}

function store_event($event){
  // TODO: MySQL storage
}
