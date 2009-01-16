#!/usr/bin/env php
<?php 

// http://www.cafeoto.co.uk/programme.shtm
// http://www.timeout.com/london/venue/14759/cafe_oto.html
// http://www.remotegoat.co.uk/venue_view.php?uid=25219

require __DIR_ . '/../main.inc.php';

$calendar_name = 'Southbank Centre';

$xml = url_html_xml('http://www.southbankcentre.co.uk/all-events?action=calendar&calendar_selected=2009-01-13');
//$xml = url_html_xml('../files/test.html');

$rows = $xml->xpath('//div[@id="col1"]/div[@class="list"]');

$events = array();
foreach ($rows as $row){  
  $thumbnail = first($row->xpath('a[@class="noA"]')); 

  $info = $row->div[0];
  
  $datetime = (string) first($info->xpath('*[@class="datetime"]'));
  list($start, $end) = array_map('strtotime', explode(' - ', $datetime));
  
  $summary = (string) $info->h4;
  $description = (string) first($info->xpath('*[@class="info2"]'));
  
  $venue = (string) first($info->xpath('*[@class="venue"]'));
  $price = first($info->xpath('*[@class="price"]'));
  
  //printf("\n===\n%s\n===\n", $summary);
  
  $events[] = array(
    'start' => $start,
    'end' => $end,
    'uri' => make_link($thumbnail['href']),
    'image' => make_link($thumbnail->img['src']),
    'summary' => strip_tags($summary),
    'description' => strip_tags($description),
    'location' => $venue ? $venue : $calendar_name,
    );
    
}

ical($calendar_name, $events);
