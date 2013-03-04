<?php
require_once __DIR__ . '/js_url.php';
global $js_includes;
if (!$js_includes)
    $js_includes = array();
global $exclude_facebook_js;
if (!$exclude_facebook_js)
    $js_includes[] = 'js/facebook.js';
$js_includes = array_merge(array(
    'js/jquery-1.7.2.min.js', 
    'js/bootstrap.min.js',
    'js/base.js',
    'js/google_analytics.js',
    'js/jquery-ui-1.8.23.custom.min.js',
    'js/jquery.placeholder.min.js',
    'js/autocomplete.js',
    'js/form-validation.js',
    'js/ask.js',
), $js_includes);
?>
<?php
foreach ($js_includes as $js)
    echo '<script src="' . get_file_url($js) . '"></script>' . "\n";
?>
