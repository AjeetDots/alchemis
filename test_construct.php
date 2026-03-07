<?php
$files = ['include/jpgraph-2.2/jpgraph.php', 'include/jpgraph-2.2/jpgraph_bar.php', 'include/jpgraph-2.2/jpgraph_pie.php'];
foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    preg_match_all('/class\s+([A-Za-z0-9_]+)/i', $content, $matches);
    foreach ($matches[1] as $class) {
        if (!class_exists($class, false)) { @include_once($file); }
        if (!class_exists($class, false)) continue;
        $ref = new ReflectionClass($class);
        if ($ref->hasMethod($class) && !$ref->hasMethod('__construct')) {
            echo "$file: class $class\n";
        }
    }
}
