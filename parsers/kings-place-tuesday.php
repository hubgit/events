#!/usr/bin/env php
<?php

require __DIR__ . '/../main.inc.php';

$calendar_name = 'Kings Place Tuesdays';

$url = 'http://www.kingsplace.co.uk/music/this-is-tuesday';

$dom = url_html_zend($url);

$items = $dom->query('p.prod-event');

$events = array();
foreach ($items as $item){
  $xml = simplexml_import_dom($item);
  
  $a = $xml->strong[0]->a;
  $title = (string) $a;
  $link = (string) $a['href'];

  unset($xml->strong[0]);
  unset($xml->br);
  
  $text = strip_tags((string) $xml);
  
  preg_match('/Time:\s+(.+?\))/s', $text, $matches);
  if (empty($matches))
    continue;
    
  $date_string = filter_var($matches[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
  $date_string = preg_replace('/\s+/', ' ', $date_string);
  
  $date = DateTime::createFromFormat('H:i  (l j F)', $date_string);
  
  $start = $date->getTimestamp();
  $end = $start + 3600*2; // 2hr
  
  $description = preg_replace('/\b(To benefit from lower priced seats|Time:) .*/s', '', $text);
    
  $events[] = array(
    'start' => $start,
    'end' => $end,
    'uri' => make_link($link),
    'summary' => strip_tags($title),
    'description' => strip_tags($description),
    'location' => 'Kings Place',
    );
}

ical($calendar_name, $events);