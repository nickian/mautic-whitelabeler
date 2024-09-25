<?php
/**
 * Compare versions of Mautic to see if the files whitelabeling affects have been modified.
 * 
 * These are the files overwritten by the whitelabeler:
 * 
 * - /app/bundles/CoreBundle/Resources/views/Default/base.html.twig
 * - /app/bundles/CoreBundle/Resources/views/Default/head.html.twig
 * - /app/bundles/CoreBundle/Resources/views/LeftPanel/index.html.twig
 * - /app/bundles/UserBundle/Resources/views/Security/base.html.twig
 * - /app/bundles/CoreBundle/Assets/css/app.css
 * - /app/bundles/CoreBundle/Assets/css/libraries/libraries.css
 * - /app/bundles/CoreBundle/Assets/js/1a.content.js
 * 
 * You can also compare branches on Github: https://github.com/mautic/mautic/compare/5.0.4...5.1.0
 */

require_once('whitelabeler.php');
$whitelabeler = new Whitelabeler;

// Use a trailing slash at the end
$mautic_dir = '/';

$v1_path = $mautic_dir.'5.1.0';
$v2_path = $mautic_dir.'5.1.1';

$compare = $whitelabeler->compareMauticVersions($v1_path, $v2_path);

echo '<pre>';
print_r($compare);
echo '</pre>';