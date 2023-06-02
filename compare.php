<?php

// Compare a new release of Mautic to see if the files whitelabeling affects have been modified.

require_once('whitelabeler.php');
$whitelabeler = new Whitelabeler;

$v1_path = '4.4.8';
$v2_path = '4.4.9';

$compare = $whitelabeler->compareMauticVersions($v1_path, $v2_path);

echo '<pre>';
print_r($compare);
echo '</pre>';
