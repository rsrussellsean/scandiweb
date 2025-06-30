<?php 
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: *");
// echo "Hello World";

// include 'DbConnect.php';
// $objDb = new DbConnect();
// $conn = $objDb->connect();

// print_r(file_get_contents('php://input'));


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use FastRoute\RouteCollector;
use App\Controller\GraphQL;

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // Call static handler
        echo call_user_func($handler, $vars);
        break;
}

?>