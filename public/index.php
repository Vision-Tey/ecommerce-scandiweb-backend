<?php

use App\Config\Config;
use App\Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle OPTIONS method for preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri, PHP_URL_PATH);
// Config::getConfig(), $_ENV["DB_USER"], $_ENV["DB_PWD"])
// new Database();
$conf = Config::getConfig();
$user = $conf['db']['user'];
$pass = $conf['db']['password'];

new Database($conf, $user, $pass);

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/graphql', [App\Controller\GraphQLController::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $parsedUrl
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found', 'uri' => $parsedUrl]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo json_encode(['error' => '405 Method Not Allowed', 'allowedMethods' => $allowedMethods]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$class, $method] = $handler;

        if (!class_exists($class)) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => "Class $class does not exist"]);
            exit;
        }

        $controller = new $class();

        try {
            echo $controller->$method($vars);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
        }
        break;
    default:
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        break;
}
