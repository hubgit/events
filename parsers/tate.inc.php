<?php 

require dirname(__FILE__) . '/../includes/main.inc.php';

$params = array(
  'dsd' => $date['mday'],
  'dsm' => $date['mon'],
  'dsy' => $date['year'],
  'ded' => $date['mday'],
  'dem' => $date['mon'],
  'dey' => $date['year'],
  'gal' => $gal,
  'view' => 'illustrated',
);

$url = 'http://www.tate.org.uk/calendar/calendar.jsp?' . http_build_query($params);
$xml = url_html_xml($url);
//$xml = url_html_xml('files/tate.html');

$rows = $xml->xpath('//div[@id="results"]/div/table/tr[@class="resultrow"]');
$events = array();

ical_start($calendar_name);

foreach ($rows as $row){
  $date_span = $row->td[0]->span[1]->span;
  $start = sscanf((string) $date_span->span[0], '%d.%d');
  $end = sscanf((string) $date_span->span[1], '%d.%d');
    
  $info = first($row->xpath('td[@class="resultsinfo"]'));
  $thumbnail = first($info->xpath('div[@class="resultsthumbnail"]/a'));
  
  $summary = first($info->xpath('div[@class="resultstitle"]/strong/a/span'));
  $description = first($info->xpath('div[@class="resultsdesc"]/span/span'));
  
  ical_event(array(
    'start' => gmmktime($start[0], $start[1], 0, $date['mon'], $date['mday'], $date['year']),
    'end' => gmmktime($end[0], $end[1], 0, $date['mon'], $date['mday'], $date['year']),
    'uri' => make_link($thumbnail['href']),
    'image' => make_link($thumbnail->img['src']),
    'summary' => (string) $summary,
    'description' => (string) $description,
    )); 
}

ical_end();

