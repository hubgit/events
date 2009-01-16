#!/usr/bin/env php
<?php

require __DIR_ . '/../main.inc.php';

$calendar_name = 'Housmans Bookshop';

$text = file_get_contents('http://www.housmans.com/events.php');
preg_match_all('!<(?:strong|span)[^>]*>(\d+)\..+?\((.+?)\).*?<br />\s*(?:</strong>)?(?:</span>)?(.+?)</(?:strong|span)>.*?<(?:em|span)[^>]*>(.+?)</(?:em|span)>.*?(?:<img[^>]+src="(.+?)")?!is', $text, $matches, PREG_SET_ORDER);
  
//print_r($matches); exit();
$items = array();
foreach ($matches as $item){  
  array_shift($item); 
  $items[] = array_map('fix_housmans', $item);
}

$today = strtotime(date('Y-m-d'));
$year = date('Y');

$events = array();
foreach ($events as $item){
   list($number, $type, $summary, $date) = $item;
   $datetime = preg_split('/\s*(\–|\-)\s*/', $date);
   
   if (count($datetime) < 2)
     continue;
     
   foreach ($datetime as $key => $value){
     $value = preg_replace('/^\W+/', '', $value);
     $value = preg_replace('/\W+$/', '', $value);
     $datetime[$key] = trim($value);
   }
  
   if (isset($datetime[2]) && preg_match('/([ap]m)$/i', $datetime[2], $matches))
     $datetime[1] .= $matches[1];
   
   unset($datetime[2]);
   debug($datetime);

   $date_input = implode(' ', $datetime);
   debug($date_input);
   
   $start = DateTime::createFromFormat('l jS F Ga', $date_input);
   
   if (!is_object($start))
     continue;

   debug($start->format('Y-m-d H:i:s') . "\n===\n");
   
   $start = $start->getTimestamp();
   
   $events[] = array(
     'start' => $start,
     'end' => $start + 60*60, // 1hr
     'uri' => '',
     'image' => '',
     'summary' => $summary,
     'description' => $summary,
     'location' => $calendar_name,
     );
}

ical($calendar_name, $events);

function fix_housmans($data){
  $data = html_entity_decode($data);
  $data = preg_replace('/&.+?;/', '', $data);
  $data = preg_replace('/\s+/', ' ', $data);
  $data = strip_tags($data);
  return $data;
}
