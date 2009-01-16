<?php

if (phpversion() < 5.3) // DateTime::createFromFormat
  die ('Needs PHP 5.3');

define('ROOT', __DIR__);

$paths = array(
  'libs',
  );

set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));

require_once ROOT . '/includes/functions.inc.php';

$date = getdate();