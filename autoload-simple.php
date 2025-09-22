<?php
// Super simple and robust autoloader
spl_autoload_register(function ($className) {
    // Handle App\Config\DatabaseSimple -> src/Config/DatabaseSimple.php
    if (strpos($className, 'App\\') === 0) {
        $path = 'src/' . str_replace(['App\\', '\\'], ['', '/'], $className) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
});
?>