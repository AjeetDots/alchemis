<?php
$compileDir = __DIR__ . '/app/view/templates_c';
$count = 0;
if (is_dir($compileDir)) {
    foreach (new DirectoryIterator($compileDir) as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            if (@unlink($file->getPathname())) $count++;
        }
    }
}
header('Content-Type: text/plain; charset=utf-8');
echo 'Smarty cache cleared. Deleted ' . $count . ' file(s).';
