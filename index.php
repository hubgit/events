<html>
  <head>
    <title>Event Calendars</title>
  </head>
  <body>

  <ul>
    <?php
    require_once 'includes/main.inc.php';
        
    $files = scandir(dirname(__FILE__) . '/output');
    foreach ($files as $file):
      if (strpos($file, '.') === 0)
        continue;
    ?>
      <li><a href="output/<?php print $file; ?>"><?php print basename($file); ?></a></li>
    <?php endforeach; ?>
  </ul>

  </body>
</html>
