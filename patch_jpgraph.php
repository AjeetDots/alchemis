<?php
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('include/jpgraph-2.2'));
foreach ($iter as $file) {
    if (in_array(strtolower($file->getExtension()), ['php', 'inc'])) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        // regex to find class and its body
        if (preg_match_all('/class\s+([a-zA-Z0-9_]+)(.*?)(?=(?:class\s+[a-zA-Z0-9_]+)|\z)/is', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $className = $match[1];
                $classBody = $match[2];
                
                // Does it have a PHP4 constructor?
                if (preg_match('/function\s+'.$className.'\s*\(/i', $classBody)) {
                    // Does it already have __construct?
                    if (!preg_match('/function\s+__construct\s*\(/i', $classBody)) {
                        // Needs patching
                        // Add __construct before the PHP4 constructor
                        $replacement = "    function __construct(...\$args) { call_user_func_array(array(\$this, '$className'), \$args); }\n$0";
                        $newBody = preg_replace('/function\s+'.$className.'\s*\(/i', $replacement, $classBody, 1);
                        $content = str_replace($classBody, $newBody, $content);
                    }
                }
            }
        }
        
        file_put_contents($path, $content);
        echo "Processed $path\n";
    }
}
