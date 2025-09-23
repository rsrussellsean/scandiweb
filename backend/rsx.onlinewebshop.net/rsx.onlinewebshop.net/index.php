<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require_once __DIR__ . '/vendor/autoload.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => [
            'message' => 'Failed to load dependencies: ' . $e->getMessage()
        ]
    ]);
    exit;
}

use FastRoute\RouteCollector;
use App\Controller\GraphQL;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
    $r->addRoute(['GET', 'POST'], '/api/graphql.php', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // Try to serve from src/index.php or fallback to 404
        if (file_exists(__DIR__ . '/src/index.php')) {
            require __DIR__ . '/src/index.php';
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // Call static handler
        try {
            echo call_user_func($handler, $vars);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => [
                    'message' => 'Handler error: ' . $e->getMessage()
                ]
            ]);
        }
        break;
}
?>