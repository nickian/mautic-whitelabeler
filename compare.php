<?php
// Compare a new release of Mautic to see if the files whitelabeling
// affects have been modified.

require_once('whitelabeler.php');
$whitelabeler = new Whitelabeler;

$v1_path = '/path/to/mautic-v1';
$v2_path = '/path/to/mautic-v2';

$compare = $whitelabeler->compareMauticVersions($v1_path, $v2_path);

var_dump($compare);