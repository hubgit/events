#!/usr/bin/env php
<?php 

// http://www.cafeoto.co.uk/programme.shtm
// http://www.timeout.com/london/venue/14759/cafe_oto.html
// http://www.remotegoat.co.uk/venue_view.php?uid=25219

require __DIR_ . '/../main.inc.php';

$calendar_name = 'cafe OTO';

$xml = url_html_xml('http://www.cafeoto.co.uk/programme.shtm');
//$xml = url_html_xml('files/test.html');

$rows = $xml->xpath('//td[@id="maincontent"]/table/tr');
array_shift($rows); // header
$events = array();

foreach ($rows as $row){  
  $start = fix_oto_date((string) $row->td[1]->p[1]);
  if (!$start)
    continue;
    
  $end = $start + 60*60*3; // 3hr
    
  $thumbnail = first($row->xpath('td[@id="progpics"]/a'));
  $info = first($row->xpath('td[@id="programme"]'));
  
  $summary = (string) first($info->xpath('p[@class="bandname"]'));
  //printf("\n===\n%s\n===\n", $summary);
  
  $description = $info;
  unset($description->p[0]);
  unset($description->br);
  
  $events[] = array(
    'start' => $start,
    'end' => $end,
    'uri' => make_link($thumbnail['href']),
    'image' => make_link($thumbnail->img['src']),
    'summary' => strip_tags($summary),
    'description' => strip_tags($description->asXML()),
    'location' => $calendar_name,
    ); 
}

ical($calendar_name, $events);

function fix_oto_date($input){
  $date_string = preg_replace('/•/', '', $input);
  debug($input);
  
  preg_match('/(\d+)\s+(\w+)\s+\'(\d+)\s+([0-9:]+)([AP]M)/s', $date_string, $matches);
  if (!$matches)
    continue;
  
  array_shift($matches);
  
  if (!strpos(':', $matches[3]))
    $matches[3] .= ':00';
    
  $formatted_date = vsprintf('%s %s 20%s, %s%s', $matches);
  debug($formatted_date);
  
  $date = DateTime::createFromFormat('j M Y, g:iA', $formatted_date);
  if (!$date)
    return FALSE;
    
  $time = $date->getTimestamp();
  debug(date(DATE_ATOM, $time));
  
  return $time;
}

