<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Controller/GraphQL.php';

echo \App\Controller\GraphQL::handle();
