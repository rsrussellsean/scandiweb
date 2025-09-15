<?php
// filepath: c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb\backend\autoload.php

spl_autoload_register(function ($className) {
    // Convert namespace to file path
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $className = str_replace('App' . DIRECTORY_SEPARATOR, '', $className);

    $file = __DIR__ . '/src/' . $className . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});