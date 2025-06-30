<?php
$logFile = __DIR__ . '/../error.log';

if (file_exists($logFile)) {
    echo "<pre>" . htmlspecialchars(file_get_contents($logFile)) . "</pre>";
} else {
    echo "No error log found.";
}
