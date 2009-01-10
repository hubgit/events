BEGIN:VEVENT
URL;VALUE=URI:<?php print sanical($event['uri']); ?>
DTSTART:<?php print sanical(date('Ymd\THis', $event['start'])); ?>
DTEND:<?php print sanical(date('Ymd\THis', $event['end'])); ?>
SUMMARY:<?php print sanical($event['summary']); ?>
DESCRIPTION:<?php print sanical($event['description']); ?>
END:VEVENT

