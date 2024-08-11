<?php

use App\Config\Config;
use App\Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

// Set headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle OPTIONS method for preflight requests and exit early
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Parse the request URI to get the path
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri, PHP_URL_PATH);

// Retrieve database configuration from config file
$conf = Config::getConfig();
$user = $conf['db']['user'];
$pass = $conf['db']['password'];

// Initialize the database connection
new Database($conf, $user, $pass);

// Set up FastRoute dispatcher and define routes
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/graphql', [App\Controller\GraphQLController::class, 'handle']);
});

// Dispatch the request to the appropriate route handler
$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $parsedUrl
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // Handle 404 Not Found error
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found', 'uri' => $parsedUrl]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // Handle 405 Method Not Allowed error
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo json_encode(['error' => '405 Method Not Allowed', 'allowedMethods' => $allowedMethods]);
        break;
    case FastRoute\Dispatcher::FOUND:
        // Route found, proceed to handle the request
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$class, $method] = $handler;

        // Check if the class exists
        if (!class_exists($class)) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => "Class $class does not exist"]);
            exit;
        }

        // Instantiate the controller class
        $controller = new $class();

        try {
            // Call the method on the controller and output the response
            echo $controller->$method($vars);
        } catch (Exception $e) {
            // Handle any exceptions thrown during the request handling
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
        }
        break;
    default:
        // Handle unexpected errors
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        break;
}
