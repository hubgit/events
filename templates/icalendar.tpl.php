BEGIN:VCALENDAR
VERSION:2.0
X-WR-TIMEZONE:Europe/London
X-WR-CALNAME:<?php print sanical($name); ?>
<?php foreach ($events as $event) require __DIR__ . '/event.tpl.php'; ?>
END:VCALENDAR